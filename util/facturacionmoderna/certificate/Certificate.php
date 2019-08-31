<?php

namespace util\facturacionmoderna\certificate;

class Certificate {

    private $noCertificado = NULL;
    private $certificado = NULL;
    private $privateKey = NULL;
    private $passphrase = NULL;

    /**
	 * @return the unknown_type
	 */
    public function getNoCertificado(){
        return $this->noCertificado;
    }

    /**
	 * @param unknown_type $noCertificado
	 */
    public function setNoCertificado( $noCertificado ){
        $this->noCertificado = $noCertificado;
        return $this;
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

    public function __toString(){
        $str = "No Certificado: [" . $this->noCertificado . "]\n";
        $str .= "Certificado:\n" . $this->certificado . "\n";
        
        return $str;
    }

    /**
	 * @return the unknown_type
	 */
    public function getPrivateKey(){
        return $this->privateKey;
    }

    /**
	 * @param unknown_type $privateKey
	 */
    public function setPrivateKey( $privateKey ){
        $this->privateKey = $privateKey;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getPassphrase(){
        return $this->passphrase;
    }

    /**
	 * @param unknown_type $passphrase
	 */
    public function setPassphrase( $passphrase ){
        $this->passphrase = $passphrase;
        return $this;
    } 
}