<?php

namespace util\facturacionmoderna\certificate;

use modulos\dueno\logic\DuenoCertificado;

use util\config\Config;

class CertificateReader {
    
    private $certificate = NULL;
    private $sello = NULL;

    public function __construct() {
        $this->certificate = new Certificate();
    }

    public function readCertificado( $path, $cerFile, $keyFile, $password ) {
        try {
            exec("openssl enc -in " . $path . $cerFile . " -a -A -out " . $path . $cerFile . ".txt");
            
            $this->certificado($path . $cerFile . ".txt");
            $this->noCertificado($path . $cerFile);
            $this->pemFile($path, $keyFile, $password);
        } catch ( \Exception $e ) {
            Logger($this)->error($e);
            
            throw $e;
        }
    }

    public function parseCert( $certFile, $path ) {
        $parts = pathinfo($certFile);
        $pemFileName = $parts["filename"] . ".pem";

        if ( !file_exists($path . $pemFileName) ) {
            system('openssl x509 -in ' . $path . $certFile . ' -inform DER -out ' . $path . $pemFileName . ' -outform PEM');

            if ( !file_exists($path . $pemFileName) ) {
                throw new \Exception("could not convert to pem file: " . $path . $pemFileName . " (source: [" . $certFile . "])");
            }
        }
        
        $content = file_get_contents($path . $pemFileName);
        $data = openssl_x509_parse($content, true);
        
        return $data;
    }

    public function sign( $data, $pemFile ) {
        $signature = NULL;
        $privKeyId = openssl_pkey_get_private(file_get_contents($pemFile));

        $algoritms = openssl_get_md_methods();
        
        openssl_sign($data, $signature, $privKeyId, $algoritms[8]);
        
        if ( $signature == NULL ) {
            throw new CertificateException(_("err_msg_certificado"), CertificateException::NOT_ABLE_TO_SIGN_STRING);
        }
        
        $this->sello = base64_encode($signature);
    }

    public function pemFile( $path, $keyFile, $password ) {
        exec("openssl pkcs8 -inform DET -in " . $path . $keyFile . " -passin pass:" . $password . " -out " . $path . "key.pem");
    }

    private function noCertificado( $file ) {
        $serial = exec("openssl x509 -inform DER -in $file -serial -noout") . "\n";
        
        $splitted = explode("=", $serial);
        $no = $splitted[1];
        
        // cada segundo numero nos interesa
        $counter = 2;
        $serialNo = "";

        foreach ( str_split($no) as $digit ) {
            if ( $counter % 2 != 0 ) {
                $serialNo .= $digit;
            }
            
            $counter++;
        }
        
        $this->certificate->setNoCertificado($serialNo);
    }

    private function certificado( $file ) {
        if ( !file_exists($file) ) {
            throw new CertificateException("can't read file " . $file, CertificateException::FILE_NOT_READABLE);
        }
        
        $this->certificate->setCertificado(file_get_contents($file));
    }

    public function __toString() {
        return $this->certificate->__toString();
    }

    /**
     * @return Certificate
     */
    public function getCertificate() {
        return $this->certificate;
    }

    /**
     * @return the unknown_type
     */
    public function getSello() {
        return $this->sello;
    }
}