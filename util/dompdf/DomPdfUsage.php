<?php

namespace util\dompdf;

require_once ("util/dompdf/dompdf_config.inc.php");

class DomPdfUsage {
    
    const PAPER_LETTER = "letter";
    const ORIENTATION_PORTRAIT = "portrait";
    const ORIENTATION_LANDSCAPE = "landscape";

    /**
     * Crea el Documento PDF
     * 
     * @param string $html Contenido del PDF (requerido)
     * @param string $orientation Orientaci�n del documento (opcional) Default: portrait
     * @param string $ruta Define la ruta donde se desea almacenar el archivo (opcional)
     */
    
    public function generarPDF( 
        $html, $paper = DomPdfUsage::PAPER_LETTER, $orientation = DomPdfUsage::ORIENTATION_PORTRAIT, $ruta = NULL 
    ) {
        
        try {

            spl_autoload_register('DOMPDF_autoload', true, true);

            $dompdf = new \DOMPDF();
                        
            $dompdf->set_paper($paper, $orientation);
            $dompdf->load_html($html, 'UTF-8');
            $dompdf->render();       

            $output = $dompdf->output();
            
            if ( $ruta != NULL ) {
                file_put_contents($ruta, $output);
            }
            
            return $output;
        } catch ( \Exception $e ) {
            throw $e;
        }
    }
}