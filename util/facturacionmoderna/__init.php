<?php

use modulos\log\LogLogic;
use plataforma\SysConstants;

function exception_error_handler( $errno, $errstr, $errfile, $errline ){
    /*if ( !(error_reporting() & $errno) ) {
        // This error code is not included in error_reporting
        return;
    }*/
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

date_default_timezone_set('America/Mexico_City');

set_error_handler("exception_error_handler");

chdir(dirname(__FILE__) . '/../../');

$_SERVER['REQUEST_URI'] = "/";

include 'autoloader.php';
include 'session_init.php';

LogLogic::getInstance()->setIdLogin(1);
LogLogic::getInstance()->setRemoteAddr('127.0.0.1');