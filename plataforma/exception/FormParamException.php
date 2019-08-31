<?php

namespace plataforma\exception;

class FormParamException extends \Exception {

    const CODE_MISSING_REQUIRED = 101;
    const CODE_TYPE_ERROR = 102;
    
    public function __construct ( $message = null, $code = null, $previous = null ) {
        parent::__construct($message, $code, $previous);
        header(' ', true, 400);
    }
}