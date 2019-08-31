<?php

namespace plataforma;

use plataforma\exception\IntentionalException;

use util\seguridad\Seguridad;

class RequestParam {
    
    private $get;
    private $post;
    private $filter;
    private $file;
    private $session;
    private $seguridad;
    
    public function __construct() {
        $this->get = array();
        $this->file= array();
        $this->post = array();
        $this->session = array();

        $this->seguridad = new Seguridad();
    }

	private function processRequestData($key, $valor, $required, $returnIfNot, $verificate)
	{
		if (is_array($valor)) {
			$valor = $this->sanitizeArray($valor);
		} else {
			if (function_exists('get_magic_quotes_gpc') && (get_magic_quotes_gpc() == 1)) {
				$valor = stripslashes($valor);
			}
			if ($verificate) {
				$valor = $this->sanitize($valor);
				$valor = $this->seguridad->replaceReservedWords($valor);
			}
		}

		if (is_array($valor)) {
			if ($required) {
				throw new IntentionalException(0, sprintf('El valor de: %s no puede ser vacio', $key));
			}
			return $valor;
		}

		if (strlen($valor) != 0) {
			return $valor;
		} else {
			if ($required) {
				throw new IntentionalException(0, sprintf('El valor de: %s no puede ser vacio', $key));
			} else {
				return $returnIfNot;
			}
		}
	}

    public function fromGet( $key, $required = true, $returnIfNot = "", $verificate = true ) {
        if ( !array_key_exists($key, $this->get) && !isset($this->get[$key]) ) {
            if ( $required ) {
                throw new IntentionalException(0, sprintf('El valor de: %s no puede ser vacio', $key));
            } else {
                return $returnIfNot;
            }
        }

        return $this->processRequestData($key, $this->get[$key], $required, $returnIfNot, $verificate);
    }

    public function fromGetInt( $key, $required = true, $returnIfNot = 0 ) {
        $value = $this->fromGet($key, $required, $returnIfNot);
        
        if ( !$required && strlen($value) < 1 ) {
            return $returnIfNot;
        }
        
        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        
        if ( $intValue === FALSE ) {
            throw new IntentionalException(0, sprintf('El valor de: %s debe ser un número', $key));
        }

        return $intValue;
    }

    public function fromPost( $key, $required = true, $returnIfNot = "", $verificate = true ) {
        if ( !array_key_exists($key, $this->post) && !isset($this->post[$key]) ) {
            if ( $required ) {
                throw new IntentionalException(0, sprintf('El valor de: %s no puede ser vacio', $key));
            } else {
                return $returnIfNot;
            }
        }

        return $this->processRequestData($key, $this->post[$key], $required, $returnIfNot, $verificate);
    }

    public function fromPostInt( $key, $required = true, $returnIfNot = 0 ) {
        $value = $this->fromPost($key, $required, $returnIfNot);
        
        if ( !$required && strlen($value) < 1 ) {
            return $returnIfNot;
        }
        
        $intValue = filter_var($value, FILTER_VALIDATE_INT);

        if ( $intValue === FALSE ) {
            throw new IntentionalException(0, sprintf('El valor de: %s debe ser un número', $key));
        }

        return $intValue;
    }
    
    public function fromSession( $key, $returnIfNot = NULL ) {
        return ( array_key_exists($key, $this->session) && isset($this->session[$key]) ) ? $this->session[$key] : $returnIfNot;
    }

    public function fromFile($key, $required = true, $returnIfNot = 0 ){
        if ( !array_key_exists($key, $this->file) && !isset($this->file[$key]) ) {
            if ( $required ) {
                throw new IntentionalException(0, sprintf('El valor de: %s no puede ser vacio', $key));
            } else {
                return $returnIfNot;
            }
        }
        return $this->file[$key];
    }

    private function sanitizeArray( $array ) {
        $new_array = array();

        foreach ( $array as $key => $value ) {
            $new_val = $value;

            if ( is_array($value) ) {
                $new_val = $this->sanitizeArray($value);
            } else {
                $new_val = $this->sanitize($value);
            }

            $new_array[$key] = $new_val;
        }

        return $new_array;
    }

    private function sanitize( $string ) {
        $val = strip_tags(html_entity_decode(trim(strval($string))));

        return preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $val);
    }
    
    public function setSessionParam( $key, $value ) {
        $this->session[$key] = $value;
    }

    public function unsetSessionParam( $key ) {
        unset($this->session[$key]);
    }

    public function setPostParam( $key, $value ) {
        $this->post[$key] = $value;
    }

    public function unsetPostParam( $key ) {
        unset($this->post[$key]);
    }

    public function setGetParam( $key, $value ) {
        $this->get[$key] = $value;
    }

    public function unsetGetParam( $key ) {
        unset($this->get[$key]);
    }

    public function setPost( &$post ) {
        $this->post = $post;
        return $this;
    }

    public function setGet( &$get ) {
        $this->get = $get;
        return $this;
    }

    public function setSession( &$session ) {
        $this->session = &$session;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
    
    public function getPost() {
        return $this->post;
    }
    
    public function getGet() {
        return $this->get;
    }

    public function getSession() {
        return $this->session;
    }

    public function getFile()
    {
        return $this->file;
    }
}