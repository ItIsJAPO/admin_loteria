<?php

use view\ViewElements;

use plataforma\DataAndView;

header("Content-type: application/json; charset=utf-8");

$json_response = ViewElements::toJSON($dataAndView->getData(DataAndView::JSON_DATA));

$json_error = json_last_error();

switch ( $json_error ) {
    case JSON_ERROR_NONE:
    	echo $json_response;
    break;
    case JSON_ERROR_DEPTH:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - Se ha excedido la profundidad máxima de la pila'));
    break;
    case JSON_ERROR_STATE_MISMATCH:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - JSON con formato incorrecto o inválido'));
    break;
    case JSON_ERROR_CTRL_CHAR:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - Error del carácter de control, posiblemente se ha codificado de forma incorrecta'));
    break;
    case JSON_ERROR_SYNTAX:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - Error de sintaxis'));
    break;
    case JSON_ERROR_UTF8:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - Caracteres UTF-8 mal formados, posiblemente codificados de forma incorrecta'));
    break;
    default:
    	echo json_encode(array('success' => false, 'message' => 'JSON ERROR - Error desconocido'));
    break;
}