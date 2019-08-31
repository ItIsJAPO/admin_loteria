<?php

namespace plataforma\exception;

class PermissionException extends \Exception {

	const NOT_ACCESS_MODULO = 777;
    
    public function __construct( $message = NULL, $code = NULL ) {
        parent::__construct($message, $code);
        header(' ', true, 401);
    }
}