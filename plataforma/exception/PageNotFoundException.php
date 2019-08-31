<?php

namespace plataforma\exception;

class PageNotFoundException extends \Exception {
    
    const MODULE_NOT_FOUND = 1;
    const ACTION_NOT_FOUND = 2;

    public function __construct ( $message = null, $code = null, $previous = null ) {
        parent::__construct($message, $code, $previous);
        header(' ', true, 404);
    }
}