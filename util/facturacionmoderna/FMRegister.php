<?php

namespace util\facturacionmoderna;

use util\facturacionmoderna\cancelacion\ActivarCancelacion;

use util\config\Config;

class FMRegister {

    private $username;
    private $password;
    private $cookie;
    private $debug;

    public function __construct() {
        $this->debug = Config::getInstance()->get("fact_moderna_debug") != NULL && Config::getInstance()->get("fact_moderna_debug") == "1" ? 1 : 0;
        $this->username = Config::getInstance()->get("fact_moderna_user_id");
        $this->password = Config::getInstance()->get("fact_moderna_pw");

        $query_url = "_method=POST&data%5BUser%5D%5Busername%5D=AVR130708I2A&data%5BUser%5D%5Bpassword%5D=f87b4bf91dcce3b11f62ee509c0e97b35a216a6f";

        $pageFacturacionModerna = $this->doRequest('https://partners.facturacionmoderna.com/', true, $query_url);

        $this->cookie = 'CAKEPHP=' . $this->getVariable($pageFacturacionModerna, 'Set-Cookie', 'CAKEPHP=', ';');
    }

    public function registerDueno( $dueno ) {
        if ( $this->debug ) {
            Logger($this)->log('fm_register', "registrar dueno en facturacion moderna START");
            Logger($this)->log('fm_register', "cookie: " . $this->cookie);
        }
                
        // first is to check if the rfc is already registered
        if ( $this->rfcExists($dueno->getRfc()) ) {
            return true;
        }
            
        // register rfc
        // and asign timbres
        $data = array();
        $data['_method'] = "POST";
        $data['data[Customer][limite_timbres]'] = 50;
        $data['data[Customer][limite_inferior]'] = 50;
        $data['data[Customer][taxnumber]'] = $dueno->getRfc();
        $data['data[Customer][tipo_conexion]'] = "web_services";
        $data['data[Customer][email_contacto]'] = $dueno->getCorreo();
        $data['data[Customer][razon_social]'] = $dueno->getRazonSocial();
        $data['data[Customer][nombre_contacto]'] = $dueno->getNombreCompleto();

        if ( $this->debug ) {
            Logger($this)->log('fm_register', $data);
        }

        $response = $this->doRequest('https://partners.facturacionmoderna.com/customers/add', true, $data, 0);
        
        // to verify, if the rfc was registered successfully here comes another request, to check if the rfc exists...
        if ( !$this->rfcExists($dueno->getRfc()) ) {
            return false;
        }

        $activacionCancelacion = new ActivarCancelacion();
        $activacionCancelacion->getXmlRequestActivacion($dueno);

        if ( $this->debug ) {
            Logger($this)->log('fm_register', "registrar dueno en facturacion moderna END");
        }
    }

    private function rfcExists( $rfc ) {
        $response = $this->doRequest('https://partners.facturacionmoderna.com/customers', true, '_method=POST&data%5BCustomer%5D%5Btaxnumber%5D=' . $rfc, 0);
        
        $existing = $this->parseResponseUserExists($response);
        
        if ( intval($existing) > 0 ) {
            return true;
        }
        
        return false;
    }

    private function dataArrayToRequestString( $data ) {
        $str = "";
        $isFirst = true;
        
        foreach ( $data as $key => $value ) {
            if ( !$isFirst ) {
                $str .= "&";
            }
            
            $str .= urlencode($key) . "=" . urlencode($value);
            $isFirst = false;
        }
        
        return $str;
    }

    private function parseResponseUserExists( $response ) {
        // <strong>Total registros:</strong> 1
        // <strong>Total registros:</strong> 0
        $matches = array();

        preg_match_all("/<strong>Total registros:<\/strong> (\d+)/", $response, $matches);
        
        return $matches[1][0];
    }

    private function getVariable( $text, $variable, $field, $limit = '"' ) {
        $var = "";
        $position = strpos($text, $variable);
        
        if ( $position !== false ) {
            $value = strpos($text, $field, $position + strlen($variable));
            
            if ( $value !== false ) {
                $valueClose = strpos($text, $limit, $value + strlen($field));
                
                $var = substr($text, ($value + strlen($field)), ($valueClose - ($value + strlen($field))));
            }
        }

        return $var;
    }

    public function doRequest( $url, $post = false, $varpost = null, $header = 1 ) {
        $curl = curl_init();

        if ( $varpost != NULL && is_array($varpost) ) {
            $varpost = $this->dataArrayToRequestString($varpost);
        }

        if ( $post ) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $varpost);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if ( !empty($this->cookie) ) {
            Logger($this)->log('fm_register', $this->cookie);
            curl_setopt($curl, CURLOPT_COOKIE, $this->cookie);
        }

        if ( $header == 0 ) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Host: partners.facturacionmoderna.com',
                'Origin: https://partners.facturacionmoderna.com',
                'Referer: https://partners.facturacionmoderna.com/'
            ));
        }

        curl_setopt(
            $curl, 
            CURLOPT_USERAGENT, 
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; InfoPath.2; .NET CLR 2.0.50727)"
        );

        $result = curl_exec($curl);

        if ( $this->debug ) {
            Logger($this)->log('fm_register', $result);
        }
        
        $errorCode = 0;
        $errorMessage = "";
        
        if ( curl_errno($curl) ) {
            $errorCode = curl_errno($curl);
            $errorMessage = curl_error($curl);
        }
        
        curl_close($curl);
        
        if ( $errorCode ) {
            throw new \Exception($errorMessage, $errorCode);
        }

        return $result;
    }
}