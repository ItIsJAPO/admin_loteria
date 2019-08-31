<?php

namespace plataforma;

use plataforma\exception\PageNotFoundException;
use plataforma\exception\IntentionalException;

use util\logger\SystemLog;

use util\config\Config;

class MainController {

    /**
     *
     * @param array $paramsFiles
     * @param array $paramsPost
     * @param array $paramsGet
     * @throws Exception
     * @return \plataforma\DataAndView
     */
    public function &performRequest( &$paramsFiles, &$paramsPost, &$paramsGet, &$sessParams, &$serverParams, $modulo ) {
        if ( $modulo == NULL ) {
            return NULL;
        }

        $fullyQualifiedClassName = "modulos\\" . $modulo . "\\Controller";
        $methodName = array_key_exists('acciones', $paramsGet) && isset($paramsGet['acciones']) && ($paramsGet['acciones'] != "") ? $paramsGet['acciones'] : "perform";
        $view_called = $methodName;
        $methodName = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $methodName))));

        $refClass = NULL;
        $reflectionMethod = NULL;

        if ( !file_exists(str_replace('\\', '/', $fullyQualifiedClassName) . ".php") ) {
            throw new PageNotFoundException(
                "Not found [" . $fullyQualifiedClassName . "]",
                PageNotFoundException::MODULE_NOT_FOUND
            );
        }

        $refClass = new \ReflectionClass($fullyQualifiedClassName);

        if ( !$refClass->hasMethod($methodName) ) {
            throw new PageNotFoundException(
                "Not found [" . $methodName . "]",
                PageNotFoundException::ACTION_NOT_FOUND
            );
        }

        $parentClass = $refClass->getParentClass();

        if ( $parentClass->hasMethod($methodName) && strcmp($methodName, "perform") !== 0 ) {
            throw new PageNotFoundException("Not found [" . $methodName . "]", PageNotFoundException::ACTION_NOT_FOUND);
        }

        $methodBefore = $refClass->getMethod('beforeFilter');
        $methodAction = $refClass->getMethod($methodName);

        $controller = $refClass->newInstance();

        if ( !($controller instanceof ControllerBase) ) {
            throw new IntentionalException(IntentionalException::CONTROLLER_NOT_FOUND);
        }

        $controller->setParamsGet($paramsGet);
        $controller->setParamsPost($paramsPost);
        $controller->setFileParams($paramsFiles);
        $controller->setSessParams($sessParams);
        $controller->setServerParams($serverParams);
        $controller->getDataAndView()->show($view_called);
        $controller->getDataAndView()->setModulo($modulo);
        $controller->getDataAndView()->setModuloNombre($modulo);

        $go_on = $methodBefore->invoke($controller);

        if ( $go_on !== false ) {
            $methodAction->invoke($controller);
        }

        $system_log = new SystemLog();

        $system_log->saveLog($paramsPost, $paramsGet, $sessParams, $modulo . '.' . $view_called);

        return $controller->getDataAndView();
    }
}