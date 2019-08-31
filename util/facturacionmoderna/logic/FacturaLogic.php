<?php

namespace util\facturacionmoderna\logic;

use util\facturacionmoderna\FacturacionModerna;
use util\facturacionmoderna\logic\Factura;

use modulos\contrato\logic\Contrato;

use util\config\Config;

class FacturaLogic {
    
    private $debug;
    private $user_id;
    private $url_timbrado;
    private $user_password;
    
    /**
     * Niveles de debug:
     * 0 - No almacenar
     * 1 - Almacenar mensajes SOAP en archivo log.
     */
    public function __construct( $debug = 0 ) {
        $this->url_timbrado = Config::getInstance()->get("fact_moderna_url_timbrada");
        $this->user_password = Config::getInstance()->get("fact_moderna_pw");
        $this->user_id = Config::getInstance()->get("fact_moderna_user_id");
        $this->debug = $debug;
    }
    
    public function cancelarFactura( Factura $factura, $debug = false ) {
        $parametros = array(
            'UserPass' => $this->user_password,
            'emisorRFC' => $factura->getRfc(),
            'UserID' => $this->user_id
        );
        
        $cliente = new FacturacionModerna($this->url_timbrado, $parametros, $debug);
        
        if ( $cliente->cancelar($factura->getUid()) ) {
            return true;
        } else {
            throw new \Exception(_("factura_cancelar_err") . " [" . $cliente->ultimoCodigoError . "] " . $cliente->ultimoError);
        }
    }
    
    /**
     * Este método permite calcular el importe de una factura dado el total, permite 3 tipos de calculos seg�n el desglose de impuestos necesario.
     * @param Factura $factura
     * @param string $cliente
     * @param string $opcion
     * @version 2.0
     * @author Carlos Duarte
     */
    public function calcularImporte( Factura &$factura, $cliente = false ) {
        $opcion = $factura->getTipoDesglose();
        
        if ( $cliente ) {
        	$opcion = 2; // si se va a realizar una factura a los clientes de MisRentas solo se aplica el método de IVA
        }

        //se calcula el desglose de impuestos de acuerdo a la opción seleccionada por el usuario
        switch ( $opcion ) {
        	case Contrato::TIPO_CONTRATO_OPTION_ONE:
    	       $this->calculaImporteSinIVA($factura);
    	    break;
        	case Contrato::TIPO_CONTRATO_OPTION_TWO:
    	       $this->calculaImporteConIVA($factura);
    	    break;
        	case Contrato::TIPO_CONTRATO_OPTION_THREE:
               $this->calculaImporteConIVAyRetenciones($factura);
            break;
            case Contrato::TIPO_CONTRATO_OPTION_FOUR:
    	       $this->calculaImporteConRetencionesSinIVARet($factura);
    	    break;     
        }
    }
    
    private function calculaImporteSinIVA( Factura &$factura ) {
        $preIva = 0;
        $total = $factura->getTotal();
        $iva = number_format(round($preIva, 2), 2, '.', '');
    
        $factura->setImporte($total);
        $factura->setSubTotal($total);
        $factura->setImporteIva($iva);
        $factura->setValorUnitario($total);
    }    
    
    private function calculaImporteConIVA( Factura &$factura ) {
        $tasaIva = floatVal(Config::getInstance()->get("IVA_TASA")) / 100;

        $tasaIvaComp = 1 + $tasaIva;
        $total = $factura->getTotal();
        $preSubtotal = $total / $tasaIvaComp;
        $subTotal = number_format(round($preSubtotal, 2), 2, '.', '');
        $preIva = $preSubtotal * $tasaIva;
        $iva = number_format(round($preIva, 2), 2, '.', '');
        
        $factura->setImporteIva($iva);
        $factura->setImporte($subTotal);
        $factura->setSubTotal($subTotal);
        $factura->setValorUnitario($subTotal);
    }
    
    private function calculaImporteConRetencionesSinIVARet( Factura &$factura ) {
        $factorInverso = floatval(Config::getInstance()->get("FACTOR_INVERSO"));
        
        foreach ( $factura->getRetencionesOtro() as $value ) {
            $factorInverso = $factorInverso - ($value->getPorcentaje() / 100);
        }
        
        $tasaIva = floatVal(Config::getInstance()->get("IVA_TASA")) / 100;
        $total = floatval($factura->getTotal());
        $preImporte = $total / $factorInverso;
        
        // otras retenciones
        foreach ( $factura->getRetencionesOtro() as $retencion ) {
            $retencionFactura = new Retencion();
            $retencionFactura->setId($retencion->getId());
            $retencionFactura->setNombreImpuesto($retencion->getConcepto());
            $retencionFactura->setPorcentajeImpuesto($retencion->getPorcentaje());
            $retencionFactura->setValorImpuesto(number_format(round($preImporte * ($retencion->getPorcentaje() / 100), 2), 2, '.', ''));
            
            $factura->addRetencionOtroCalculado($retencionFactura);
        }
        
        $importe = number_format(round($preImporte, 2), 2, '.', '');
        
        $preIva = $importe * $tasaIva;

        $iva = number_format(round($preIva, 2), 2, '.', '');
        
        $tasaIsrRet = floatVal(Config::getInstance()->get("ISR_TASA"));
        $preImporteisrRet = $importe * $tasaIsrRet;
        $ImporteisrRet = number_format(round($preImporteisrRet, 2), 2, '.', '');

        $tasaIvaRet = floatVal(0);
        $preIvaRet = $iva * $tasaIvaRet;
        $ImporteivaRet = number_format(round($preIvaRet, 2), 2, '.', '');
        
        $difTotal = $this->calcularDifRound($total, $importe, $iva, $ImporteisrRet, $ImporteivaRet, $factura);
        
        if ( $difTotal > 0 ) {
            $importe = $importe + $difTotal;
            $importe = number_format(round($importe, 2), 2, '.', '');
        } else if ( $difTotal < 0 ) {
            $importe = $importe - abs($difTotal);
            $importe = number_format(round($importe, 2), 2, '.', '');
        }
        
        $factura->setImporteIva($iva);
        $factura->setImporte($importe);
        $factura->setSubTotal($importe);
        $factura->setValorUnitario($importe);
        $factura->setImporteIvaRet($ImporteivaRet);
        $factura->setImporteIsrRet($ImporteisrRet);
    }

    private function calculaImporteConIVAyRetenciones( Factura &$factura ) {
        $factorInverso = floatval(Config::getInstance()->get("FACTOR_INVERSO"));
        
        foreach ( $factura->getRetencionesOtro() as $value ) {
            $factorInverso = $factorInverso - ($value->getPorcentaje() / 100);
        }
        
        $tasaIva = floatVal(Config::getInstance()->get("IVA_TASA")) / 100;
        $total = floatval($factura->getTotal());
        $preImporte = $total / $factorInverso;
        
        // otras retenciones
        foreach ( $factura->getRetencionesOtro() as $retencion ) {
            $retencionFactura = new Retencion();
            $retencionFactura->setId($retencion->getId());
            $retencionFactura->setNombreImpuesto($retencion->getConcepto());
            $retencionFactura->setPorcentajeImpuesto($retencion->getPorcentaje());
            $retencionFactura->setValorImpuesto(number_format(round($preImporte * ($retencion->getPorcentaje() / 100), 2), 2, '.', ''));
            
            $factura->addRetencionOtroCalculado($retencionFactura);
        }
        
        $importe = number_format(round($preImporte, 2), 2, '.', '');
        
        $preIva = $importe * $tasaIva;

        $iva = number_format(round($preIva, 2), 2, '.', '');
        
        $tasaIsrRet = floatVal(Config::getInstance()->get("ISR_TASA"));
        $preImporteisrRet = $importe * $tasaIsrRet;
        $ImporteisrRet = number_format(round($preImporteisrRet, 2), 2, '.', '');
        $tasaIvaRet = floatVal(Config::getInstance()->get("IVA_RET_TASA"));
        $preIvaRet = $iva * $tasaIvaRet;
        $ImporteivaRet = number_format(round($preIvaRet, 2), 2, '.', '');
        
        $difTotal = $this->calcularDifRound($total, $importe, $iva, $ImporteisrRet, $ImporteivaRet, $factura);
        
        if ( $difTotal > 0 ) {
            $importe = $importe + $difTotal;
            $importe = number_format(round($importe, 2), 2, '.', '');
        } else if ( $difTotal < 0 ) {
            $importe = $importe - abs($difTotal);
            $importe = number_format(round($importe, 2), 2, '.', '');
        }
        
        $factura->setImporteIva($iva);
        $factura->setImporte($importe);
        $factura->setSubTotal($importe);
        $factura->setValorUnitario($importe);
        $factura->setImporteIvaRet($ImporteivaRet);
        $factura->setImporteIsrRet($ImporteisrRet);
    }

    private function calcularDifRound( $total, $importe, $iva, $isrRet, $ivaRet, Factura $factura ) {
        $opTotal = $importe + $iva - $isrRet - $ivaRet;
        
        foreach ( $factura->getRetencionesOtrosCalculados() as $value ) {
            $opTotal -= $value->getValorImpuesto();
        }
        
        if ( $total != $opTotal ) {
            $difTotal = $total - $opTotal;
        } else {
            $difTotal = 0;
        }
        
        return round($difTotal, 2);
    }
}