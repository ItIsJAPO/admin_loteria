<?php

namespace util\facturacionmoderna\view;

use util\facturacionmoderna\logic\Factura;
use modulos\inquilino\logic\Inquilino;
use modulos\dueno\logic\Dueno;
use util\pdf\PdfGenerator;
use util\config\Config;

class PDFPrinter {

    public static function printPDF( Factura $factura, $userId, $ruta, $logo, Inquilino $inquilino, Dueno $dueno ) {
        $pathXML = Config::getInstance()->get('path_sistema') . "/comprobantes/" . $userId . "/" . $factura->getIdDueno() . "/";
        $xml = simplexml_load_file($pathXML . $factura->getUid() . ".xml");
        
        try {
            $pdfGen = new PdfGenerator();
            $pdfGen->setSubstitutionData(array($xml, $logo, $dueno, $userId, $inquilino, $factura->getFolio(), $factura->getMetodoDePago()));
            $pdfGen->setDestinationFile($ruta);
            $pdfGen->setView("modulos/factura_contrato/view/factura_pdf.php");
            $pdfGen->generatePdf();
        } catch ( Exception $e ) {
            throw $e;
        }
    }
}