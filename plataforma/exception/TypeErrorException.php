<?php

namespace plataforma\exception;

use plataforma\exception\FormParamException;

class TypeErrorException extends FormParamException {
    public function __construct() {
        parent::__construct(
            'El tipo de dato enviado en la peticion es incorrecto', 
            self::CODE_TYPE_ERROR
        );
    }
}