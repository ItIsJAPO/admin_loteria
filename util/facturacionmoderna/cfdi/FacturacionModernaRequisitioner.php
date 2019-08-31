<?php

namespace util\facturacionmoderna\cfdi;

use util\facturacionmoderna\FacturacionModerna;

use util\uploadfile\FileUploaderHelper;

use plataforma\IntentionalException;

use util\arrays\ArrayTools;

use util\config\Config;

class FacturacionModernaRequisitioner {

    private $debug;
    private $urlTimbrado;
    private $userId;
    private $userPassword;
    
    private $factura = NULL;
    
    private $opciones = array();
    
    /* Response */
    private $cfdiTimbrado = NULL;
    private $pdf = NULL;
    private $txt = NULL;
    private $png = NULL;

    public function __construct( $opciones = NULL ) {
        $this->debug = Config::getInstance()->get("fact_moderna_debug") != NULL && Config::getInstance()->get("fact_moderna_debug") == "1" ? 1 : 0;
        $this->urlTimbrado = Config::getInstance()->get("fact_moderna_url_timbrada");
        $this->userPassword = Config::getInstance()->get("fact_moderna_pw");
        $this->userId = Config::getInstance()->get("fact_moderna_user_id");
        
        if ( $opciones != NULL ) {
            $this->opciones = $opciones;
        } else {
            $this->opciones = array(
                'generarCBB' => false,
                'generarPDF' => false,
                'generarTXT' => false
            );
        }
    }

    public function timbrar( &$factura, $cfdi ) {
        $parametros = array(
            'emisorRFC' => $factura->getRfc(),
            'UserPass' => $this->userPassword,
            'UserID' => $this->userId
        );
        
        $cliente = new FacturacionModerna($this->urlTimbrado, $parametros, $this->debug);

        if ( $this->debug ) {
            Logger($this)->debug($cfdi);
        }
        
        if ( $cliente->timbrar($cfdi, $this->opciones) ) {
            $comprobante = FileUploaderHelper::pathOfDirectoryOf('comprobantes/' . $factura->getIdUsuario() . "/" . $factura->getIdDueno());
            
            FileUploaderHelper::createDirectoryIfNotExists($comprobante);
            
            $filename = $cliente->UUID;
            
            if ( $cliente->xml ) {
                if ( !file_put_contents($comprobante . $filename . ".xml", $cliente->xml) ) {
                    $err = error_get_last();
                    $type = $err['type'];
                    $message = $err['message'];
                    $file = $err['file'];
                    $line = $err['line'];
                    
                    Logger($this)->error("XML could not be written");
                    Logger($this)->error("Erro Type: " . $type);
                    Logger($this)->error($message);
                    Logger($this)->error($file . "(" . $line . ")");
                }

                $this->cfdiTimbrado = $cliente->xml;
            }
            
            if ( isset($cliente->pdf) ) {
                if ( !file_put_contents($comprobante . $filename . ".pdf", $cliente->pdf) ) {
                    $err = error_get_last();
                    $type = $err['type'];
                    $message = $err['message'];
                    $file = $err['file'];
                    $line = $err['line'];
                    
                    Logger($this)->error("PDF could not be written");
                    Logger($this)->error("Erro Type: " . $type);
                    Logger($this)->error($message);
                    Logger($this)->error($file . "(" . $line . ")");
                }

                $this->pdf = $cliente->pdf;
            }
            
            if ( isset($cliente->png) ) {
                if ( !file_put_contents($comprobante . $filename . ".png", $cliente->png) ) {
                    $err = error_get_last();
                    $type = $err['type'];
                    $message = $err['message'];
                    $file = $err['file'];
                    $line = $err['line'];
                    
                    Logger($this)->error("PNG could not be written");
                    Logger($this)->error("Erro Type: " . $type);
                    Logger($this)->error($message);
                    Logger($this)->error($file . "(" . $line . ")");
                }

                $this->png = $cliente->png;
            }

            $factura->setUid($cliente->UUID);
        
        } else {
            throw new IntentionalException(intval($cliente->ultimoCodigoError), _("factura_generate_err") . " [" . $cliente->ultimoCodigoError . "]" . $cliente->ultimoError);
        }
    }

    public function cancelar( $rfc_emisor, $uuid ) {
        $parametros = array(
            'UserPass' => $this->userPassword,
            'emisorRFC' => $rfc_emisor,
            'UserID' => $this->userId
        );
        
        $cliente = new FacturacionModerna($this->urlTimbrado, $parametros, $this->debug);
        
        if ( !$cliente->cancelar($uuid) ) {
            throw new IntentionalException(intval($cliente->ultimoCodigoError), _("factura_cancelar_err") . " [" . $cliente->ultimoCodigoError . "] " . $cliente->ultimoError);
        }
    }

    public function addOption( $key, $value ) {
        $this->opciones[$key] = $value;
    }

    public function optionGenerarPDF( $value = true ) {
        $this->opciones['generarPDF'] = $value;
    }

    public function optionGenerarCBB( $value = true ) {
        $this->opciones['generarCBB'] = $value;
    }

    public function optionGenerarTXT( $value = true ) {
        $this->opciones['generarTXT'] = $value;
    }

    /**
	 * @return the unknown_type
	 */
    public function getDebug() {
        return $this->debug;
    }

    /**
	 * @param unknown_type $debug
	 */
    public function setDebug( $debug ) {
        $this->debug = $debug;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getFactura() {
        return $this->factura;
    }

    /**
	 * @param unknown_type $factura
	 */
    public function setFactura( $factura ) {
        $this->factura = $factura;
        return $this;
    }

    /**
	 * @return string
	 */
    public function getCfdiTimbrado() {
        return $this->cfdiTimbrado;
    }

    /**
	 * @return the unknown_type
	 */
    public function getPdf() {
        return $this->pdf;
    }

    /**
	 * @return the unknown_type
	 */
    public function getTxt() {
        return $this->txt;
    }

    /**
	 * @return the unknown_type
	 */
    public function getPng() {
        return $this->png;
    }
}