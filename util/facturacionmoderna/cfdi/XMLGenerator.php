<?php

namespace util\facturacionmoderna\cfdi;

use util\facturacionmoderna\certificate\CertificateReader;

use util\facturacionmoderna\logic\Retencion;

use modulos\dueno\logic\Dueno;

use repository\Contrato;

use util\config\Config;

class XMLGenerator {
    
    private $xmlString = NULL;
    private $xmlFileName = NULL;
    private $cadenaOriginal = NULL;
    
    public function generateXMLComplement( $complemento_pago, $fecha_pago, $uuid, $impuesto_saldo_anterior, $impuesto_saldo_insoluto ) {
        $cfdi = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:pago10="http://www.sat.gob.mx/Pagos" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsdhttp://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd" Version="3.3"></cfdi:Comprobante>');

        $cfdi->addAttribute("Fecha", $complemento_pago->getFecha());
        $cfdi->addAttribute("Sello", "");
        $cfdi->addAttribute("NoCertificado", $complemento_pago->getNoCertificado());
        $cfdi->addAttribute("Certificado", $complemento_pago->getCertificado());
        $cfdi->addAttribute("SubTotal", $complemento_pago->getSubTotal());
        $cfdi->addAttribute("Moneda", $complemento_pago->getMoneda());
        $cfdi->addAttribute("Total", 0);
        $cfdi->addAttribute("TipoDeComprobante", $complemento_pago->getTipoDeComprobante());
        $cfdi->addAttribute("LugarExpedicion", $complemento_pago->getCodigoPostal());

        // EMISOR
        $emisor = $cfdi->addChild("Emisor", NULL);

        $emisor->addAttribute("Rfc", $complemento_pago->getRfc());
        $emisor->addAttribute("Nombre", $complemento_pago->getNombre());
        $emisor->addAttribute("RegimenFiscal", $complemento_pago->getRegimenFiscal()->getClave());

        // RECEPTOR
        $receptor = $cfdi->addChild("Receptor", NULL);

        $receptor->addAttribute("Rfc", $complemento_pago->getRfcR());
        $receptor->addAttribute("Nombre", $complemento_pago->getNombreR());
        $receptor->addAttribute("UsoCFDI", "P01"); // Por definir
        
        // CONCEPTOS
        $conceptos = $cfdi->addChild("Conceptos", NULL);
        
        $concepto = $conceptos->addChild("Concepto", NULL);
        
        $concepto->addAttribute("ClaveProdServ", "84111506"); // Servicios de facturación
        $concepto->addAttribute("Cantidad", $complemento_pago->getCantidad());
        $concepto->addAttribute("ClaveUnidad", "ACT"); // Actividad
        $concepto->addAttribute("Descripcion", "Pago");
        $concepto->addAttribute("ValorUnitario", $complemento_pago->getValorUnitario());
        $concepto->addAttribute("Importe", $complemento_pago->getImporte());
        
        // COMPLEMENTO DE PAGO
        $complemento = $cfdi->addChild("Complemento", NULL);

        $pagos = $complemento->addChild("Pagos", NULL, "http://www.sat.gob.mx/Pagos");

        $pagos->addAttribute("Version", "1.0");

        $pago = $pagos->addChild("Pago", NULL, "http://www.sat.gob.mx/Pagos");

        $pago->addAttribute("FechaPago", $fecha_pago);
        $pago->addAttribute("FormaDePagoP", $complemento_pago->getFormaDePago()->getClave());
        $pago->addAttribute("MonedaP", "MXN");
        $pago->addAttribute("Monto", $complemento_pago->getTotal());

        $docto_relacionado = $pago->addChild("DoctoRelacionado", NULL, "http://www.sat.gob.mx/Pagos");

        $docto_relacionado->addAttribute("IdDocumento", $uuid);
        $docto_relacionado->addAttribute("Folio", $complemento_pago->getFolio());
        $docto_relacionado->addAttribute("MonedaDR", "MXN");
        $docto_relacionado->addAttribute("MetodoDePagoDR", $complemento_pago->getMetodoDePago());
        $docto_relacionado->addAttribute("NumParcialidad", $complemento_pago->getFolio());
        $docto_relacionado->addAttribute("ImpSaldoAnt", $impuesto_saldo_anterior);
        // por ser un solo documento relacionado, se omite el nodo ImpPagado
        //$docto_relacionado->addAttribute("ImpPagado", "");
        // por ser un solo documento relacionado, el calculo del nodo es el ImpSaldoAnt - Monto
        $docto_relacionado->addAttribute("ImpSaldoInsoluto", number_format($impuesto_saldo_insoluto, 2, '.', ''));

        $this->xmlString = $cfdi->asXML();

        $cfdi->attributes()->Sello = $this->getSello($complemento_pago->getIdDueno());

        $this->xmlString = $cfdi->asXML();
    }

    public function generateXML( $factura ) {
        $cfdi = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd" xmlns:implocal="http://www.sat.gob.mx/implocal" Version="3.3"></cfdi:Comprobante>');

        $cfdi->addAttribute("Fecha", $factura->getFecha());
        $cfdi->addAttribute("Sello", "");
        $cfdi->addAttribute("FormaPago", $factura->getFormaDePago()->getClave());
        $cfdi->addAttribute("NoCertificado", $factura->getNoCertificado());
        $cfdi->addAttribute("Certificado", $factura->getCertificado());
        $cfdi->addAttribute("CondicionesDePago", $factura->getCondicionesDePago());
        $cfdi->addAttribute("SubTotal", $factura->getSubTotal());
        $cfdi->addAttribute("Moneda", $factura->getMoneda());
        $cfdi->addAttribute("Total", $factura->getTotal());
        $cfdi->addAttribute("TipoDeComprobante", $factura->getTipoDeComprobante());
        $cfdi->addAttribute("MetodoPago", $factura->getMetodoDePago());
        $cfdi->addAttribute("LugarExpedicion", $factura->getCodigoPostal());

        // EMISOR
        $emisor = $cfdi->addChild("Emisor", NULL);

        $emisor->addAttribute("Rfc", $factura->getRfc());
        $emisor->addAttribute("Nombre", $factura->getNombre());
        $emisor->addAttribute("RegimenFiscal", $factura->getRegimenFiscal()->getClave());

        // RECEPTOR
        $receptor = $cfdi->addChild("Receptor", NULL);

        $receptor->addAttribute("Rfc", $factura->getRfcR());
        $receptor->addAttribute("Nombre", $factura->getNombreR());
        $receptor->addAttribute("UsoCFDI", $factura->getUsoCfdi()->getClave());
        
        // CONCEPTOS
        $conceptos = $cfdi->addChild("Conceptos", NULL);
        
        $concepto = $conceptos->addChild("Concepto", NULL);
        
        $concepto->addAttribute("ClaveProdServ", "80131801"); // administracion de propiedades
        $concepto->addAttribute("Cantidad", $factura->getCantidad());
        $concepto->addAttribute("ClaveUnidad", "E48");
        $concepto->addAttribute("Unidad", $factura->getUnidad());
        $concepto->addAttribute("Descripcion", $factura->getDescripcion());
        $concepto->addAttribute("ValorUnitario", $factura->getValorUnitario());
        $concepto->addAttribute("Importe", $factura->getImporte());
        
        // IMPUESTOS DEL CONCEPTO
        $total_retenidos = NULL;

        $impuestos_concepto = $concepto->addChild("Impuestos", NULL);

        $traslados_concepto = $impuestos_concepto->addChild("Traslados");
        
        $traslado = $traslados_concepto->addChild("Traslado");

        $traslado->addAttribute("Base", $factura->getSubTotal());
        $traslado->addAttribute("TipoFactor", "Tasa");
        $traslado->addAttribute("TasaOCuota", $factura->getTasa());
        $traslado->addAttribute("Impuesto", $factura->getImpuesto());
        $traslado->addAttribute("Importe", $factura->getImporteIva());

        if ( ($factura->getTipoDesglose() == Contrato::TIPO_CONTRATO_OPTION_THREE) || ($factura->getTipoDesglose() == Contrato::TIPO_CONTRATO_OPTION_FOUR) ) {
            $retenciones = $impuestos_concepto->addChild("Retenciones", NULL);

            $retencion_iva = $retenciones->addChild("Retencion", NULL);

            $base_iva = number_format($factura->getImporteIvaRet() / $factura->getTasa(), 2, '.', '');
            
            $retencion_iva->addAttribute("Base", $base_iva == "0.00" ? "0.000001" : $base_iva);
            $retencion_iva->addAttribute("Impuesto", $factura->getImpuesto());
            $retencion_iva->addAttribute("TipoFactor", "Tasa");
            $retencion_iva->addAttribute("TasaOCuota", $factura->getTasa());
            $retencion_iva->addAttribute("Importe", $factura->getImporteIvaRet());

            $retencion_isr = $retenciones->addChild("Retencion", NULL);

            $retencion_isr->addAttribute("Base", $factura->getSubTotal());
            $retencion_isr->addAttribute("Impuesto", "001");
            $retencion_isr->addAttribute("TipoFactor", "Tasa");
            $retencion_isr->addAttribute("TasaOCuota", "0.100000");
            $retencion_isr->addAttribute("Importe", $factura->getImporteIsrRet());

            $total_retenidos = $factura->getImporteIvaRet() + $factura->getImporteIsrRet();
        }

        // CUENTA PREDIAL
        if ( ($factura->getCuentaPredial() != NULL) && ($factura->getCuentaPredial() != "") ) {
            $cuenta_predial = $concepto->addChild("CuentaPredial", NULL);

            $cuenta_predial->addAttribute("Numero", $factura->getCuentaPredial());
        }
                
        // IMPUESTOS
        $impuestos = $cfdi->addChild("Impuestos", NULL);

        if ( ($factura->getTipoDesglose() == Contrato::TIPO_CONTRATO_OPTION_THREE) || ($factura->getTipoDesglose() == Contrato::TIPO_CONTRATO_OPTION_FOUR) ) {
            $retenciones = $impuestos->addChild("Retenciones", NULL);

            $retencion_iva = $retenciones->addChild("Retencion", NULL);

            $retencion_iva->addAttribute("Impuesto", $factura->getImpuesto());
            $retencion_iva->addAttribute("Importe", $factura->getImporteIvaRet());

            $retencion_isr = $retenciones->addChild("Retencion", NULL);

            $retencion_isr->addAttribute("Impuesto", "001");
            $retencion_isr->addAttribute("Importe", $factura->getImporteIsrRet());
        }

        if ( $total_retenidos != NULL ) {
            $impuestos->addAttribute("TotalImpuestosRetenidos", number_format($total_retenidos, 2, '.', ''));
        }

        $impuestos->addAttribute("TotalImpuestosTrasladados", $factura->getImporteIva());

        // TRASLADOS
        $traslados = $impuestos->addChild("Traslados");
        
        $traslado = $traslados->addChild("Traslado");

        $traslado->addAttribute("TipoFactor", "Tasa");
        $traslado->addAttribute("TasaOCuota", $factura->getTasa());
        $traslado->addAttribute("Impuesto", $factura->getImpuesto());
        $traslado->addAttribute("Importe", $factura->getImporteIva());

        // RETENCIONES OTROS
        $retenciones_otro = $factura->getRetencionesOtro();

        if ( !empty($retenciones_otro) ) {
            $complemento = $cfdi->addChild("Complemento", NULL);
            
            $totalRetenciones = 0;

            $impuestosLocales = $complemento->addChild("ImpuestosLocales", NULL, "http://www.sat.gob.mx/implocal");

            foreach ( $retenciones_otro as $retencion ) {
                $retencionesLocales = $impuestosLocales->addChild("RetencionesLocales", NULL, "http://www.sat.gob.mx/implocal");

                $retencionesLocales->addAttribute('ImpLocRetenido', $retencion['nombre']);
                $retencionesLocales->addAttribute('TasadeRetencion', $retencion['porcentaje']);
                $retencionesLocales->addAttribute('Importe', $retencion['impuesto']);

                $totalRetenciones += $retencion['impuesto'];
            }

            $impuestosLocales->addAttribute("version", "1.0");
            $impuestosLocales->addAttribute("TotaldeRetenciones", number_format($totalRetenciones, 2, '.', ''));
            $impuestosLocales->addAttribute("TotaldeTraslados", 0);
        }
                
        $this->xmlString = $cfdi->asXML();

        $cfdi->attributes()->Sello = $this->getSello($factura->getIdDueno());

        $this->xmlString = $cfdi->asXML();
    }

    private function getSello( $dueno_id ) {
        $this->cadenaOriginalXSL10();

        $certReader = new CertificateReader();
        $certReader->sign($this->cadenaOriginal, Config::getInstance()->get("path_sistema") . '/csd/' . $dueno_id . "/key.pem");
        
        return $certReader->getSello();
    }

    private function cadenaOriginalXSL10() {
        $xslDoc = new \DOMDocument();
        $xslDoc->load(dirname(__FILE__) . "/xsl/cadenaoriginal_3_3.xslt");
        
        $xmlDoc = new \DOMDocument();
        $xmlDoc->loadXML($this->xmlString);
        
        $proc = new \XSLTProcessor();
        $proc->importStyleSheet($xslDoc);
        
        $dDoc = $proc->transformToDoc($xmlDoc);

        $firstChild = $dDoc->firstChild;
        $text = $firstChild->wholeText;

        $this->cadenaOriginal = trim($text);
    }

    public function getXmlString() {
        return $this->xmlString;
    }

    public function setXmlString( $xmlString ) {
        $this->xmlString = $xmlString;
        return $this;
    }

    public function getXmlFileName() {
        return $this->xmlFileName;
    }

    public function setXmlFileName( $xmlFileName ) {
        $this->xmlFileName = $xmlFileName;
        return $this;
    }

    public function getCadenaOriginal() {
        return $this->cadenaOriginal;
    }

    public function setCadenaOriginal( $cadenaOriginal ) {
        $this->cadenaOriginal = $cadenaOriginal;
        return $this;
    }
}