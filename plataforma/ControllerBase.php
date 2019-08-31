<?php

namespace plataforma;

use plataforma\DataAndView;

abstract class ControllerBase {
    
    protected $serverParams;
    
    /** plataforma\DataAndView */
    protected $dataAndView;
    
    protected $requestMethod;
    
    protected $config = NULL;
    
    protected $formErrors = NULL;
    
    protected $requestParams;  

    public function __construct() {
        $pathOfModule = get_class($this);
        $pathOfModule = substr($pathOfModule, 0, strrpos($pathOfModule, "\\")) . "/";
        $pathOfModule = str_replace("\\", "/", $pathOfModule);

        $this->dataAndView = new DataAndView();
        $this->requestParams = new RequestParam();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->dataAndView->setPathOfModule($pathOfModule);
    }

    /*
     * @return array an array: { 'view' => nombre del archivo que hay que mostrar 'data' => todos los datos de mostrar en el view (recomendablemente es un array associativo) 'template' => nombre del template, si se quiere sobreescribir
     */
    /**
     * En este metodo se pone los datos en <code>DataAndView</code> <code>$dataAndView</code>
     */
    public abstract function perform();

    public abstract function beforeFilter();

    public function setParamsPost( &$paramsPost ) {
        $this->requestParams->setPost($paramsPost);
    }

    public function setParamsGet( &$paramsGet ) {
        $this->requestParams->setGet($paramsGet);
    }

    public function setSessParams( &$sessParams ) {
        $this->requestParams->setSession($sessParams);
    }

    public function setFileParams(&$paramFile)
    {
         $this->requestParams->setFile($paramFile);
    }

    public function &getServerParams() {
        return $this->serverParams;
    }

    public function setServerParams( &$serverParams ) {
        $this->serverParams = &$serverParams;
    }
    
    /**
     * @return plataforma\DataAndView
     */
    public function &getDataAndView() {
        return $this->dataAndView;
    }

    /**
     * @param DataAndView $dataAndView
     */
    public function setDataAndView( DataAndView &$dataAndView ) {
        $this->dataAndView = &$dataAndView;
    }

    /**
     * 
     * @return 
     */
    public function getRequestMethod() {
        return $this->requestMethod;
    }

    /**
     * 
     * @return 
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * 
     * @param $config
     */
    public function setConfig( $config ) {
        $this->config = $config;
    }

    protected function &getRequestParams() {
        return $this->requestParams;
    }

    /**
     * @param RequestParam $requestParams
     */

    public function setRequestParams( RequestParam &$requestParams ) {
        $this->requestParams = $requestParams;
        return $this;
    }

    public function redirect( $url, $code = 301 ) {
        $file = NULL;
        $line = NULL;
        
        if ( !headers_sent($file, $line) ) {
            header("Location: " . $url, $code);
        } else {
            Logger()->error("Redirect not possible: headers already sent.(" . $file . "::" . $line . ")");
            throw new \Exception("Redirect not possible: headers already sent.");
        }
        
        $this->dataAndView->setTemplate('empty');
    }

    public function handleException( $exception, $print_logger = false, $template='error_view') {
        if ( $print_logger ) {
            Logger()->error($exception);
        }

        if ( $template == 'error_view' ) {
            $this->dataAndView->setTemplate('error_view');
        } else {
            $this->dataAndView->setTemplate($template);
        }
        $message = $exception->getMessage();
        $this->dataAndView->addData('message', $message);
    }

    public function handleJsonException( $exception, $data = 'default', $print_logger = false ) {
        if ( $print_logger ) {
            Logger()->error($exception);
        }
        if($data =='default') {
            $message = $exception->getMessage();
            $this->dataAndView->addData(DataAndView::JSON_DATA, array(
                'type' => 'danger',
                'message' => $message,
                'code' => $exception->getCode()
            ));
        }else{
            $this->dataAndView->addData(DataAndView::JSON_DATA,$data);
        }
    }
    public function activateNotification( $type, $message ){
        $this->requestParams->setSessionParam(SysConstants::DATA_AND_VIEW_PARAM_NOTIFICATION, json_encode(array(
            "type" => $type,
            "message" => $message
        )));

        $this->dataAndView->setMessage($message);
        $this->dataAndView->setSuccess($type);
    }
    public function view($view){
        $this->dataAndView->show($view);
    }
}