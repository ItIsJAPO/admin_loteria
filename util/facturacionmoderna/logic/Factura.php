<?php

namespace util\facturacionmoderna\logic;

use modulos\dueno\logic\Dueno;
use modulos\inquilino\logic\Inquilino;
use modulos\contrato\logic\Pago;
use view\ViewElements;
use modulos\propiedad\logic\Propiedad;
use util\config\Config;
use util\arrays\ArrayTools;
use util\metodo_pago\MetodoPago;
use util\metodo_pago\MetodoPagoDao;

class Factura {
    
    const PERSONA_FISICA = "REGIMEN GENERAL DE LEY PERSONAS FISICAS";
    const PERSONA_MORAL = "REGIMEN GENERAL DE LEY PERSONAS MORALES";
    
    const ESTATUS_FACTURADA = 1;
    const ESTATUS_CANCELADA = 0;
    /* Factura está timbrada pero no se ha podido generar el pdf  */
    const ESTATUS_FACTURADA_NO_PDF = 2;
    const ENVIADA_SI = 1;
    const ENVIADA_NO = 0;

    const PUE = 1;
    const PPD = 2;
    
    private $idUsuario = NULL;
    private $idDueno = NULL;
    private $idContrato = NULL;
    private $idInquilino = NULL;
    private $inquiNombreComplet = NULL;
    private $noPago = NULL;
    private $conceptoPago = NULL;
    private $tipoDesglose = NULL;
    private $uid = NULL;
    private $estatus = NULL;
    private $enviada = NULL;
    
    // [Encabezado]
    
    private $serie = NULL;
    private $fecha = NULL;
    private $folio = NULL;
    private $tipoDeComprobante = "INGRESO";
    private $formaDePago = "PAGO EN UNA SOLA EXHIBICION";
    private $metodoDePago = NULL;
    private $metodoDePagoClave = NULL;
    private $condicionesDePago = "CONTADO";
    private $NumCtaPago = "NO IDENTIFICADO";
    private $subTotal = NULL;
    private $descuento = "0.00";
    private $total = NULL;
    private $Moneda = "MXN";
    private $TipoCambio = NULL;
    private $noCertificado = NULL;
    private $LugarExpedicion = NULL;
    
    // [Datos Adicionales]
    
    private $tipoDocumento = "RECIBO DE ARRENDAMIENTO";
    private $numeropedido = NULL;
    private $observaciones = NULL;
    private $TransID = NULL;
    
    // [Emisor]
    
    private $rfc = NULL;
    private $nombre = NULL;
    private $RegimenFiscal = NULL;
    
    // [DomicilioFiscal]
    
    private $calle = NULL;
    private $noExterior = NULL;
    private $noInterior = NULL;
    private $colonia = NULL;
    private $localidad = NULL;
    private $municipio = NULL;
    private $estado = NULL;
    private $pais = "México";
    private $codigoPostal = NULL;
    
    // [ExpedidoEn]
    
    private $calleExp = NULL;
    private $noExteriorExp = NULL;
    private $noInteriorExp = NULL;
    private $coloniaExp = NULL;
    private $localidadExp = NULL;
    private $municipioExp = NULL;
    private $estadoExp = NULL;
    private $paisExp = NULL;
    private $codigoPostalExp = NULL;
    
    // [Receptor]
    
    private $rfcR = NULL;
    private $nombreR = NULL;
    /**
     * Se refiere al tipo de persona de persona fisica o moral
     * Sirve para saber si se aplica la retencion de impuestos o no
     */
    private $RegimenFiscalR = NULL;
    
    // [Domicilio]
    
    private $calleRec = NULL;
    private $noExteriorRec = NULL;
    private $noInteriorRec = NULL;
    private $coloniaRec = NULL;
    private $localidadRec = NULL;
    private $municipioRec = NULL;
    private $estadoRec = NULL;
    private $paisRec = NULL;
    private $codigoPostalRec = NULL;
    
    // [DatosAdicionales]
    
    private $noCliente = NULL;
    private $email = NULL;
    
    // [Concepto]
    
    private $cantidad = NULL;
    private $unidad = "NO APLICA";
    private $noIdentificacion = NULL;
    private $descripcion = NULL;
    private $valorUnitario = NULL;
    private $importe = NULL;
    private $CuentaPredial = "NO APLICA";
    
    // [ImpuestoTrasladado]
    
    private $impuesto = "IVA";
    private $importeIva = NULL;
    private $tasa = NULL;
    
    // [ImpuestoRetenido]
    
    private $impuestoIvaRet = "IVA";
    private $importeIvaRet = NULL;
    
    // [ImpuestoRetenido]
    
    private $impuestoIsrRet = "ISR";
    private $importeIsrRet = NULL;
    
    private $fechaFinalPeriodoPago = NULL;
    
    private $retencionesOtro = array();
    /** array<Retencion> */
    private $retencionesOtrosCalculados = array();
    
    private $certificado = NULL;
    private $sello = NULL;
    
    private $dueno = NULL;
    
    /**
     * @var MetodoPago
     */
    private $metodoPago = NULL;

    // tine complementos de pago
    private $has_complements = NULL;

    public function __toString(){
        return ArrayTools::arrayToString(get_object_vars($this));
    }
    
    public function setFechaFinalPeriodoPago( $fecha, $format = NULL ){
        if ( $fecha instanceof \DateTime )
            $this->fechaFinalPeriodoPago = $fecha;
        else {
            if ($format == NULL)
                $format = "Y-m-d";
            
            try {
                $this->fechaFinalPeriodoPago = \DateTime::createFromFormat($format, $fecha);
            } catch (\Exception $e) {
                $this->fechaFinalPeriodoPago = new \DateTime();
            }
        }
    }

    /**
     * 
     * @return 
     */
    public function getIdUsuario(){
        return $this->idUsuario;
    }

    /**
     * 
     * @param $idUsuario
     */
    public function setIdUsuario( $idUsuario ){
        $this->idUsuario = $idUsuario;
    }

    /**
     * 
     * @return 
     */
    public function getIdInquilino(){
        return $this->idInquilino;
    }

    /**
     * 
     * @param $idInquilino
     */
    public function setIdInquilino( $idInquilino ){
        $this->idInquilino = $idInquilino;
    }

    /**
     * 
     * @return 
     */
    public function getInquiNombreComplet(){
        return $this->inquiNombreComplet;
    }

    /**
     * 
     * @param $inquiNombreComplet
     */
    public function setInquiNombreComplet( $inquiNombreComplet ){
        $this->inquiNombreComplet = $inquiNombreComplet;
    }

    
    /**
     * 
     * @return 
     */
    public function getIdDueno(){
        return $this->idDueno;
    }

    /**
     * 
     * @param $idDueno
     */
    public function setIdDueno( $idDueno ){
        $this->idDueno = $idDueno;
    }

    
    /**
     * 
     * @return 
     */
    public function getIdContrato(){
        return $this->idContrato;
    }

    /**
     * 
     * @param $idContrato
     */
    public function setIdContrato( $idContrato ){
        $this->idContrato = $idContrato;
    }

    /**
     * 
     * @return 
     */
    public function getNoPago(){
        return $this->noPago;
    }

    /**
     * 
     * @param $noPago
     */
    public function setNoPago( $noPago ){
        $this->noPago = $noPago;
    }

    /**
     * 
     * @return 
     */
    public function getConceptoPago(){
        return $this->conceptoPago;
    }

    /**
     * 
     * @param $conceptoPago
     */
    public function setConceptoPago( $conceptoPago ){
        $this->conceptoPago = $conceptoPago;
    }

    /**
     * 
     * @return 
     */
    public function getTipoDesglose(){
        return $this->tipoDesglose;
    }

    /**
     * 
     * @param $tipoDesglose
     */
    public function setTipoDesglose( $tipoDesglose ){
        $this->tipoDesglose = $tipoDesglose;
    }

    
    /**
     * 
     * @return 
     */
    public function getUid(){
        return $this->uid;
    }

    /**
     * 
     * @param $uid
     */
    public function setUid( $uid ){
        $this->uid = $uid;
    }

    /**
     * 
     * @return 
     */
    public function getEstatus(){
        return $this->estatus;
    }

    /**
     * 
     * @param $estatus
     */
    public function setEstatus( $estatus ){
        $this->estatus = $estatus;
    }

    /**
     * 
     * @return 
     */
    public function getEnviada(){
        return $this->enviada;
    }

    /**
     * 
     * @param $enviada
     */
    public function setEnviada( $enviada ){
        $this->enviada = $enviada;
    }

    /**
     * 
     * @return 
     */
    public function getSerie(){
        return $this->serie;
    }

    /**
     * 
     * @param $serie
     */
    public function setSerie( $serie ){
        $this->serie = $serie;
    }

    /**
     * 
     * @return 
     */
    public function getFecha(){
        return $this->fecha;
    }

    /**
     * 
     * @param $fecha
     */
    public function setFecha( $fecha ){
        $this->fecha = $fecha;
    }

    /**
     * 
     * @return 
     */
    public function getFolio(){
        return $this->folio;
    }

    /**
     * 
     * @param $folio
     */
    public function setFolio( $folio ){
        $this->folio = $folio;
    }

    /**
     * 
     * @return 
     */
    public function getTipoDeComprobante(){
        return $this->tipoDeComprobante;
    }

    /**
     * 
     * @param $tipoDeComprobante
     */
    public function setTipoDeComprobante( $tipoDeComprobante ){
        $this->tipoDeComprobante = $tipoDeComprobante;
    }

    /**
     * 
     * @return 
     */
    public function getFormaDePago(){
        return $this->formaDePago;
    }

    /**
     * 
     * @param $formaDePago
     */
    public function setFormaDePago( $formaDePago ){
        $this->formaDePago = $formaDePago;
    }

    /**
     *
     * @return
     */
    public function getMetodoDePago(){
        return $this->metodoDePago;
    }
    
    /**
     *
     * @param $metodoDePago
     */
    public function setMetodoDePago( $metodoDePago ){
        $dao = new MetodoPagoDao();
        $this->metodoPago = $dao->byId($metodoDePago);
        $this->metodoDePago = utf8_encode($this->metodoPago->getConcepto());
        $this->metodoDePagoClave = $this->metodoPago->getClave();
    }
    
    /**
     * 
     * @return 
     */
    public function getMetodoDePagoClave(){
        return $this->metodoDePagoClave;
    }

    /**
     * 
     * @param $metodoDePago
     */
    public function setMetodoDePagoClave( $metodoDePagoClave ){
        $this->metodoDePagoClave = $metodoDePagoClave;
    }
    
    /**
     * 
     * @return 
     */
    public function getCondicionesDePago(){
        return $this->condicionesDePago;
    }

    /**
     * 
     * @param $condicionesDePago
     */
    public function setCondicionesDePago( $condicionesDePago ){
        $this->condicionesDePago = $condicionesDePago;
    }

    /**
     * 
     * @return 
     */
    public function getNumCtaPago(){
        return $this->NumCtaPago;
    }

    /**
     * 
     * @param $NumCtaPago
     */
    public function setNumCtaPago( $NumCtaPago ){
        $this->NumCtaPago = $NumCtaPago;
    }

    /**
     * 
     * @return 
     */
    public function getSubTotal(){
        return $this->subTotal;
    }

    /**
     * 
     * @param $subTotal
     */
    public function setSubTotal( $subTotal ){
        $this->subTotal = $subTotal;
    }

    /**
     * 
     * @return 
     */
    public function getDescuento(){
        return $this->descuento;
    }

    /**
     * 
     * @param $descuento
     */
    public function setDescuento( $descuento ){
        $this->descuento = $descuento;
    }

    /**
     * 
     * @return 
     */
    public function getTotal(){
        return $this->total;
    }

    /**
     * 
     * @param $total
     */
    public function setTotal( $total ){
        $this->total = $total;
    }

    /**
     * 
     * @return 
     */
    public function getMoneda(){
        return $this->Moneda;
    }

    /**
     * 
     * @param $Moneda
     */
    public function setMoneda( $Moneda ){
        $this->Moneda = $Moneda;
    }

    /**
     * 
     * @return 
     */
    public function getTipoCambio(){
        return $this->TipoCambio;
    }

    /**
     * 
     * @param $TipoCambio
     */
    public function setTipoCambio( $TipoCambio ){
        $this->TipoCambio = $TipoCambio;
    }

    /**
     * 
     * @return 
     */
    public function getNoCertificado(){
        return $this->noCertificado;
    }

    /**
     * 
     * @param $noCertificado
     */
    public function setNoCertificado( $noCertificado ){
        $this->noCertificado = $noCertificado;
    }

    /**
     * 
     * @return 
     */
    public function getLugarExpedicion(){
        return $this->LugarExpedicion;
    }

    /**
     * 
     * @param $LugarExpedicion
     */
    public function setLugarExpedicion( $LugarExpedicion ){
        $this->LugarExpedicion = $LugarExpedicion;
    }

    /**
     * 
     * @return 
     */
    public function getTipoDocumento(){
        return $this->tipoDocumento;
    }

    /**
     * 
     * @param $tipoDocumento
     */
    public function setTipoDocumento( $tipoDocumento ){
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * 
     * @return 
     */
    public function getNumeropedido(){
        return $this->numeropedido;
    }

    /**
     * 
     * @param $numeropedido
     */
    public function setNumeropedido( $numeropedido ){
        $this->numeropedido = $numeropedido;
    }

    /**
     * 
     * @return 
     */
    public function getObservaciones(){
        return $this->observaciones;
    }

    /**
     * 
     * @param $observaciones
     */
    public function setObservaciones( $observaciones ){
        $this->observaciones = $observaciones;
    }

    /**
     * 
     * @return 
     */
    public function getTransID(){
        return $this->TransID;
    }

    /**
     * 
     * @param $TransID
     */
    public function setTransID( $TransID ){
        $this->TransID = $TransID;
    }

    /**
     * 
     * @return 
     */
    public function getRfc(){
        return $this->rfc;
    }

    /**
     * 
     * @param $rfc
     */
    public function setRfc( $rfc ){
        $this->rfc = $rfc;
    }

    /**
     * 
     * @return 
     */
    public function getNombre(){
        return $this->nombre;
    }

    /**
     * 
     * @param $nombre
     */
    public function setNombre( $nombre ){
        $this->nombre = $nombre;
    }

    /**
     * 
     * @return 
     */
    public function getRegimenFiscal(){
        return $this->RegimenFiscal;
    }

    /**
     * 
     * @param $RegimenFiscal
     */
    public function setRegimenFiscal( $RegimenFiscal ){
        $this->RegimenFiscal = $RegimenFiscal;
    }

    /**
     * 
     * @return 
     */
    public function getCalle(){
        return $this->calle;
    }

    /**
     * 
     * @param $calle
     */
    public function setCalle( $calle ){
        $this->calle = $calle;
    }

    /**
     * 
     * @return 
     */
    public function getNoExterior(){
        return $this->noExterior;
    }

    /**
     * 
     * @param $noExterior
     */
    public function setNoExterior( $noExterior ){
        $this->noExterior = $noExterior;
    }

    /**
     * 
     * @return 
     */
    public function getNoInterior(){
        return $this->noInterior;
    }

    /**
     * 
     * @param $noInterior
     */
    public function setNoInterior( $noInterior ){
        $this->noInterior = $noInterior;
    }

    /**
     * 
     * @return 
     */
    public function getColonia(){
        return $this->colonia;
    }

    /**
     * 
     * @param $colonia
     */
    public function setColonia( $colonia ){
        $this->colonia = $colonia;
    }

    /**
     * 
     * @return 
     */
    public function getLocalidad(){
        return $this->localidad;
    }

    /**
     * 
     * @param $localidad
     */
    public function setLocalidad( $localidad ){
        $this->localidad = $localidad;
    }

    /**
     * 
     * @return 
     */
    public function getMunicipio(){
        return $this->municipio;
    }

    /**
     * 
     * @param $municipio
     */
    public function setMunicipio( $municipio ){
        $this->municipio = $municipio;
    }

    /**
     * 
     * @return 
     */
    public function getEstado(){
        return $this->estado;
    }

    /**
     * 
     * @param $estado
     */
    public function setEstado( $estado ){
        $this->estado = $estado;
    }

    /**
     * 
     * @return 
     */
    public function getPais(){
        return $this->pais;
    }

    /**
     * 
     * @param $pais
     */
    public function setPais( $pais ){
        $this->pais = $pais;
    }

    /**
     * 
     * @return 
     */
    public function getCodigoPostal(){
        return $this->codigoPostal;
    }

    /**
     * 
     * @param $codigoPostal
     */
    public function setCodigoPostal( $codigoPostal ){
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * 
     * @return 
     */
    public function getCalleExp(){
        return $this->calleExp;
    }

    /**
     * 
     * @param $calleExp
     */
    public function setCalleExp( $calleExp ){
        $this->calleExp = $calleExp;
    }

    /**
     * 
     * @return 
     */
    public function getNoExteriorExp(){
        return $this->noExteriorExp;
    }

    /**
     * 
     * @param $noExteriorExp
     */
    public function setNoExteriorExp( $noExteriorExp ){
        $this->noExteriorExp = $noExteriorExp;
    }

    /**
     * 
     * @return 
     */
    public function getNoInteriorExp(){
        return $this->noInteriorExp;
    }

    /**
     * 
     * @param $noInteriorExp
     */
    public function setNoInteriorExp( $noInteriorExp ){
        $this->noInteriorExp = $noInteriorExp;
    }

    /**
     * 
     * @return 
     */
    public function getColoniaExp(){
        return $this->coloniaExp;
    }

    /**
     * 
     * @param $coloniaExp
     */
    public function setColoniaExp( $coloniaExp ){
        $this->coloniaExp = $coloniaExp;
    }

    /**
     * 
     * @return 
     */
    public function getLocalidadExp(){
        return $this->localidadExp;
    }

    /**
     * 
     * @param $localidadExp
     */
    public function setLocalidadExp( $localidadExp ){
        $this->localidadExp = $localidadExp;
    }

    /**
     * 
     * @return 
     */
    public function getMunicipioExp(){
        return $this->municipioExp;
    }

    /**
     * 
     * @param $municipioExp
     */
    public function setMunicipioExp( $municipioExp ){
        $this->municipioExp = $municipioExp;
    }

    /**
     * 
     * @return 
     */
    public function getEstadoExp(){
        return $this->estadoExp;
    }

    /**
     * 
     * @param $estadoExp
     */
    public function setEstadoExp( $estadoExp ){
        $this->estadoExp = $estadoExp;
    }

    /**
     * 
     * @return 
     */
    public function getPaisExp(){
        return $this->paisExp;
    }

    /**
     * 
     * @param $paisExp
     */
    public function setPaisExp( $paisExp ){
        $this->paisExp = $paisExp;
    }

    /**
     * 
     * @return 
     */
    public function getCodigoPostalExp(){
        return $this->codigoPostalExp;
    }

    /**
     * 
     * @param $codigoPostalExp
     */
    public function setCodigoPostalExp( $codigoPostalExp ){
        $this->codigoPostalExp = $codigoPostalExp;
    }

    /**
     * 
     * @return 
     */
    public function getRfcR(){
        return $this->rfcR;
    }

    /**
     * 
     * @param $rfcR
     */
    public function setRfcR( $rfcR ){
        $this->rfcR = $rfcR;
    }

    /**
     * 
     * @return 
     */
    public function getNombreR(){
        return $this->nombreR;
    }

    /**
     * 
     * @param $nombreR
     */
    public function setNombreR( $nombreR ){
        $this->nombreR = $nombreR;
    }

    /**
     * 
     * @return 
     */
    public function getRegimenFiscalR(){
        return $this->RegimenFiscalR;
    }

    /**
     * 
     * @param $RegimenFiscalR
     */
    public function setRegimenFiscalR( $RegimenFiscalR ){
        $this->RegimenFiscalR = $RegimenFiscalR;
    }

    
    /**
     * 
     * @return 
     */
    public function getCalleRec(){
        return $this->calleRec;
    }

    /**
     * 
     * @param $calleRec
     */
    public function setCalleRec( $calleRec ){
        $this->calleRec = $calleRec;
    }

    /**
     * 
     * @return 
     */
    public function getNoExteriorRec(){
        return $this->noExteriorRec;
    }

    /**
     * 
     * @param $noExteriorRec
     */
    public function setNoExteriorRec( $noExteriorRec ){
        $this->noExteriorRec = $noExteriorRec;
    }

    /**
     * 
     * @return 
     */
    public function getNoInteriorRec(){
        return $this->noInteriorRec;
    }

    /**
     * 
     * @param $noInteriorRec
     */
    public function setNoInteriorRec( $noInteriorRec ){
        $this->noInteriorRec = $noInteriorRec;
    }

    /**
     * 
     * @return 
     */
    public function getColoniaRec(){
        return $this->coloniaRec;
    }

    /**
     * 
     * @param $coloniaRec
     */
    public function setColoniaRec( $coloniaRec ){
        $this->coloniaRec = $coloniaRec;
    }

    /**
     * 
     * @return 
     */
    public function getLocalidadRec(){
        return $this->localidadRec;
    }

    /**
     * 
     * @param $localidadRec
     */
    public function setLocalidadRec( $localidadRec ){
        $this->localidadRec = $localidadRec;
    }

    /**
     * 
     * @return 
     */
    public function getMunicipioRec(){
        return $this->municipioRec;
    }

    /**
     * 
     * @param $municipioRec
     */
    public function setMunicipioRec( $municipioRec ){
        $this->municipioRec = $municipioRec;
    }

    /**
     * 
     * @return 
     */
    public function getEstadoRec(){
        return $this->estadoRec;
    }

    /**
     * 
     * @param $estadoRec
     */
    public function setEstadoRec( $estadoRec ){
        $this->estadoRec = $estadoRec;
    }

    /**
     * 
     * @return 
     */
    public function getPaisRec(){
        return $this->paisRec;
    }

    /**
     * 
     * @param $paisRec
     */
    public function setPaisRec( $paisRec ){
        $this->paisRec = $paisRec;
    }

    /**
     * 
     * @return 
     */
    public function getCodigoPostalRec(){
        return $this->codigoPostalRec;
    }

    /**
     * 
     * @param $codigoPostalRec
     */
    public function setCodigoPostalRec( $codigoPostalRec ){
        $this->codigoPostalRec = $codigoPostalRec;
    }

    /**
     * 
     * @return 
     */
    public function getNoCliente(){
        return $this->noCliente;
    }

    /**
     * 
     * @param $noCliente
     */
    public function setNoCliente( $noCliente ){
        $this->noCliente = $noCliente;
    }

    /**
     * 
     * @return 
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * 
     * @param $email
     */
    public function setEmail( $email ){
        $this->email = $email;
    }

    /**
     * 
     * @return 
     */
    public function getCantidad(){
        return $this->cantidad;
    }

    /**
     * 
     * @param $cantidad
     */
    public function setCantidad( $cantidad ){
        $this->cantidad = $cantidad;
    }

    /**
     * 
     * @return 
     */
    public function getUnidad(){
        return $this->unidad;
    }

    /**
     * 
     * @param $unidad
     */
    public function setUnidad( $unidad ){
        $this->unidad = $unidad;
    }

    /**
     * 
     * @return 
     */
    public function getNoIdentificacion(){
        return $this->noIdentificacion;
    }

    /**
     * 
     * @param $noIdentificacion
     */
    public function setNoIdentificacion( $noIdentificacion ){
        $this->noIdentificacion = $noIdentificacion;
    }

    /**
     * 
     * @return 
     */
    public function getDescripcion(){
        return $this->descripcion;
    }

    /**
     * 
     * @param $descripcion
     */
    public function setDescripcion( $descripcion ){
        $this->descripcion = $descripcion;
    }

    /**
     * 
     * @return 
     */
    public function getValorUnitario(){
        return $this->valorUnitario;
    }

    /**
     * 
     * @param $valorUnitario
     */
    public function setValorUnitario( $valorUnitario ){
        $this->valorUnitario = $valorUnitario;
    }

    /**
     * 
     * @return 
     */
    public function getImporte(){
        return $this->importe;
    }

    /**
     * 
     * @param $importe
     */
    public function setImporte( $importe ){
        $this->importe = $importe;
    }

    /**
     * 
     * @return 
     */
    public function getCuentaPredial(){
        return $this->CuentaPredial;
    }

    /**
     * 
     * @param $CuentaPredial
     */
    public function setCuentaPredial( $CuentaPredial ){
        $this->CuentaPredial = $CuentaPredial;
    }

    /**
     * 
     * @return 
     */
    public function getImpuesto(){
        return $this->impuesto;
    }

    /**
     * 
     * @param $impuesto
     */
    public function setImpuesto( $impuesto ){
        $this->impuesto = $impuesto;
    }

    /**
     * 
     * @return 
     */
    public function getImporteIva(){
        return $this->importeIva;
    }

    /**
     * 
     * @param $importeIva
     */
    public function setImporteIva( $importeIva ){
        $this->importeIva = $importeIva;
    }

    /**
     * 
     * @return 
     */
    public function getTasa(){
        return $this->tasa;
    }

    /**
     * 
     * @param $tasa
     */
    public function setTasa( $tasa ){
        $this->tasa = $tasa;
    }

    /**
     * 
     * @return 
     */
    public function getImpuestoIvaRet(){
        return $this->impuestoIvaRet;
    }

    /**
     * 
     * @param $impuestoIvaRet
     */
    public function setImpuestoIvaRet( $impuestoIvaRet ){
        $this->impuestoIvaRet = $impuestoIvaRet;
    }

    /**
     * 
     * @return 
     */
    public function getImporteIvaRet(){
        return $this->importeIvaRet;
    }

    /**
     * 
     * @param $importeIvaRet
     */
    public function setImporteIvaRet( $importeIvaRet ){
        $this->importeIvaRet = $importeIvaRet;
    }

    /**
     * 
     * @return 
     */
    public function getImpuestoIsrRet(){
        return $this->impuestoIsrRet;
    }

    /**
     * 
     * @param $impuestoIsrRet
     */
    public function setImpuestoIsrRet( $impuestoIsrRet ){
        $this->impuestoIsrRet = $impuestoIsrRet;
    }

    /**
     * 
     * @return 
     */
    public function getImporteIsrRet(){
        return $this->importeIsrRet;
    }

    /**
     * 
     * @param $importeIsrRet
     */
    public function setImporteIsrRet( $importeIsrRet ){
        $this->importeIsrRet = $importeIsrRet;
    }

    public function populate( Dueno $dueno, Inquilino $inquilino, Pago $pago, Propiedad $propiedad, $retencionesOtro = array() ){
        $this->dueno = $dueno;
        $this->idUsuario = $dueno->getIdUsuario();
        $this->idDueno = $dueno->getId();
        $this->idContrato = $pago->getIdContrato();
        $this->idInquilino = $inquilino->getId();
        $this->inquiNombreComplet = $inquilino->getNombre() . " " . $inquilino->getPaterno() . " " . $inquilino->getMaterno();
        $this->noPago = $pago->getNumPago();
        $this->conceptoPago = $pago->getConcepto();
        $this->uid = NULL;
        $this->estatus = Factura::ESTATUS_FACTURADA;
        $this->enviada = Factura::ENVIADA_NO;
        
        // [Encabezado]
        
        $this->serie = NULL;
        $this->fecha = substr(date('c'), 0, 19); // Obtiene la fecha actual en formato ISO 8601 Ej. 2014-02-11T13:29:36
        $this->folio = NULL;
        $this->tipoDeComprobante = "INGRESO";
        $this->formaDePago = "PAGO EN UNA SOLA EXHIBICION";
        $dao = new MetodoPagoDao();
        $this->metodoPago = $dao->byId($pago->getMetodoPago());
        $this->metodoDePago = utf8_encode($this->metodoPago->getConcepto());
        $this->metodoDePagoClave = $this->metodoPago->getClave();
        $this->condicionesDePago = "CONTADO";
        $this->NumCtaPago = $pago->getInfoFactura();
        $this->subTotal = NULL;
        $this->descuento = "0.00";
        $this->total = $pago->getMonto();
        $this->Moneda = "MXN";
        $this->TipoCambio = NULL;
        $this->noCertificado = $dueno->getNumCert();
        
        if ( $dueno->getLugarExpedicion() != NULL && trim($dueno->getLugarExpedicion()) != "" )
            $this->LugarExpedicion = $dueno->getLugarExpedicion(); // "CAMPECHE, MEXICO";
        else {
            $this->LugarExpedicion = $dueno->getMunicipio() . ", " . $dueno->getEstado() . ", " . $dueno->getPais();
        }
        
        // [Datos Adicionales]
        
        $this->tipoDocumento = "RECIBO DE ARRENDAMIENTO";
        $this->numeropedido = NULL;
        $this->observaciones = NULL;
        $this->TransID = NULL;
        
        // [Emisor]
        
        $this->rfc = $dueno->getRfc();
        $this->nombre = $dueno->getRazonSocial();
        $this->RegimenFiscal = "REGIMEN DE ARRENDAMIENTO";
        // [DomicilioFiscal]
        
        $this->calle = $dueno->getCalle();
        $this->noExterior = $dueno->getNumExt();
        $this->noInterior = $dueno->getNumInt();
        $this->colonia = $dueno->getColonia();
        $this->localidad = $dueno->getLocalidad();
        $this->municipio = $dueno->getMunicipio();
        $this->estado = $dueno->getEstado();
        $this->pais = $dueno->getPais();
        $this->codigoPostal = $dueno->getCp();
        
        // [ExpedidoEn]
        $this->calleExp = NULL;
        $this->noExteriorExp = NULL;
        $this->noInteriorExp = NULL;
        $this->coloniaExp = NULL;
        $this->localidadExp = NULL;
        $this->municipioExp = NULL;
        $this->estadoExp = NULL;
        $this->paisExp = NULL;
        $this->codigoPostalExp = NULL;
        
        // [Receptor]
        $this->rfcR = $inquilino->getRfc();
        $this->nombreR = $inquilino->getRazonSocial();
        $this->RegimenFiscalR = ViewElements::getRegimenFiscalStr($inquilino->getTipoPersona());
        
        // [Domicilio]
        $this->calleRec = $inquilino->getCalle();
        $this->noExteriorRec = $inquilino->getNumExt();
        $this->noInteriorRec = $inquilino->getNumInt();
        $this->coloniaRec = $inquilino->getColonia();
        $this->localidadRec = $inquilino->getLocalidad();
        $this->municipioRec = $inquilino->getMunicipio();
        
        $this->estadoRec = $inquilino->getEstado();
        $this->paisRec = $inquilino->getPais();
        $this->codigoPostalRec = $inquilino->getCp();
        
        // [DatosAdicionales]
        $this->noCliente = $inquilino->getId();
        $this->email = $inquilino->getCorreoFac();
        
        // [Concepto]
        $this->cantidad = 1;
        $this->unidad = "NO APLICA";
        $this->noIdentificacion = NULL;
        $factDesc = $pago->getConceptoFactura();
//         $this->descripcion = "ARRENDAMIENTO DE " . strtoupper($propiedad->getNombre()) . " " . ViewElements::getMonthName4Num($pago->getFecha('m')) . "-" . $pago->getFecha('Y');
        $this->descripcion = $factDesc;
        $this->valorUnitario = NULL;
        $this->importe = NULL;
        $this->CuentaPredial = $propiedad->getCuentaPredial();
        
        // [ImpuestoTrasladado]
        $this->impuesto = "IVA";
        $this->importeIva = NULL;
        $this->tasa = Config::getInstance()->get("IVA_TASA");
        

        // [ImpuestoRetenido]
        
        $this->impuestoIvaRet = "IVA";
        $this->importeIvaRet = NULL;
        

        // [ImpuestoRetenido]
        
        $this->impuestoIsrRet = "ISR";
        $this->importeIsrRet = NULL;
        
        // otras retenciones:
        $this->retencionesOtro = $retencionesOtro;
    }

    public function getArrayData(){
        $values = get_object_vars($this);
        
        $retencionesOtros = array();
        $retencionesOtrosCalculados = array();

        foreach ($this->retencionesOtro as $value)
            $retencionesOtros[] = $value->jsonSerialize();
        
        foreach ($this->retencionesOtrosCalculados as $value)
            $retencionesOtrosCalculados[] = $value->jsonSerialize();
        
        $values['retencionesOtro'] = $retencionesOtros;
        $values['retencionesOtrosCalculados'] = $retencionesOtrosCalculados;
        
        return $values;
    }

    /**
 	 * @return the array<RetencionOtro>
 	 */
    public function getRetencionesOtro(){
        return $this->retencionesOtro;
    }

    /**
 	 * @param unknown_type $retencionesOtro
 	 */
    public function setRetencionesOtro( $retencionesOtro ){
        $this->retencionesOtro = $retencionesOtro;
        return $this;
    }

    /**
 	 * @return array<Retencion>
 	 */
    public function getRetencionesOtrosCalculados(){
        return $this->retencionesOtrosCalculados;
    }
    
    public function addRetencionOtroCalculado( Retencion $retencion ) {
        if( $this->retencionesOtrosCalculados == NULL )
            $this->retencionesOtrosCalculados = array();
        
        $this->retencionesOtrosCalculados[] = $retencion;
    }

    /**
 	 * @param unknown_type $retencionesOtrosCalculados
 	 */
    public function setRetencionesOtrosCalculados( $retencionesOtrosCalculados ){
        $this->retencionesOtrosCalculados = $retencionesOtrosCalculados;
    }

    /**
 	 * @return the unknown_type
 	 */
    public function getCertificado(){
        return $this->certificado;
    }

    /**
 	 * @param unknown_type $certificado
 	 */
    public function setCertificado( $certificado ){
        $this->certificado = $certificado;
        return $this;
    }

    /**
 	 * @return the unknown_type
 	 */
    public function getSello(){
        return $this->sello;
    }

    /**
 	 * @param unknown_type $sello
 	 */
    public function setSello( $sello ){
        $this->sello = $sello;
        return $this;
    }

    /**
 	 * @return Dueno
 	 */
    public function getDueno(){
        return $this->dueno;
    }

    /**
 	 * @param Dueno $dueno
 	 */
    public function setDueno( Dueno $dueno ){
        $this->dueno = $dueno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function hasComplements() {
        return $this->has_complements;
    }

    /**
     * @param mixed $has_complements
     *
     * @return self
     */
    public function setHasComplements( $has_complements ) {
        $this->has_complements = $has_complements;
        return $this;
    }
}