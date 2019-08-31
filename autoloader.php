<?php

spl_autoload_register('plataforma_autoloader', true, true);

function plataforma_autoloader( $className ) {
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if ( $lastNsPos = strripos($className, '\\') ) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    
    require_once $fileName;
}

function DOMPDF_autoload( $class ) {
    $filename = mb_strtolower($class) . ".cls.php";
    
    if ( file_exists('util/dompdf/include/' . $filename) ) {
        require_once ('util/dompdf/include/' . $filename);
    } else {
        plataforma_autoloader($class);
    }
}