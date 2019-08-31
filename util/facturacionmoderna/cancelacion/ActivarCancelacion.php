<?php

namespace util\facturacionmoderna\cancelacion;

use util\facturacionmoderna\certificate\CertificateReader;
use util\facturacionmoderna\FacturacionModerna;

use util\uploadfile\FileUploaderHelper;

use util\seguridad\Seguridad;

use util\arrays\ArrayTools;

use util\config\Config;

class ActivarCancelacion {

    private $debug;
    private $userId;
    private $urlTimbrado;
    private $userPassword;

    public function __construct() {
        $this->debug = Config::getInstance()->get("fact_moderna_debug") != NULL && Config::getInstance()->get("fact_moderna_debug") == "1" ? 1 : 0;
        $this->urlTimbrado = Config::getInstance()->get("fact_moderna_url_timbrada");
        $this->userPassword = Config::getInstance()->get("fact_moderna_pw");
        $this->userId = Config::getInstance()->get("fact_moderna_user_id");
    }

    public function activate( $dueno ) {
        $seguridad = new Seguridad();

        $parametros = array(
            'UserPass' => $this->userPassword,
            'emisorRFC' => $dueno->getRfc(),
            'UserID' => $this->userId
        );
        
        if ( $this->debug ) {
            Logger()->debug($this->urlTimbrado);
            Logger()->debug($dueno);
        }
        
        $cliente = new FacturacionModerna($this->urlTimbrado, $parametros, $this->debug);
        
        $absolutePath = FileUploaderHelper::pathOfDirectoryOf("csd/" . $dueno->getId());
        
        return $cliente->activarCancelacion(
            $absolutePath . $dueno->getCert(),
            $absolutePath . $dueno->getKey(),
            $seguridad->XORDecrypt($dueno->getPassword())
        );
    }
}