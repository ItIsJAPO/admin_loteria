<?php

use util\logger\Logger;

function Logger() {
    return Logger::getLogger();
}

function redirect_php_errors( $errno, $errstr, $errfile, $errline ) {
	Logger()->system($errfile . "(" . $errline . ") [" . $errno . "] " . $errstr);
}

set_error_handler('redirect_php_errors');