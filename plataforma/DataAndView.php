<?php

namespace plataforma;

class DataAndView {
  
    const JSON_DATA = "JSON_DATA";
  
    private $view = null;
    private $data = null;
    private $template = null;
    private $other = array();
    private $innerView = null;
    
    private $success = true;
    private $message;
    
    /** if $add2Head !== NULL this string will be added to the head of the html doc */
    private $add2Head = NULL;
    
    private $modulo = NULL;
    private $moduloIcon = NULL;
    private $moduloNombre = NULL;
    
    private $pathOfModule = NULL;

    public function __construct( $view = null, $data = null, $template = null, $other = array() ) {
        if ( $view !== null ) {
            $this->view = $view;
        }

        if ( $data !== null ) {
            $this->data = $data;
        }

        if ( $template !== null ) {
            $this->template = $template;
        }

        if ( is_array($other) ) {
            $this->other = $other;
        }
    }

    public function setPathOfModule( $path ) {
        $this->pathOfModule = $path;
    }
    
    public function getPathOfModule() {
        return $this->pathOfModule;
    }
    
    public function show( $view ) {
        $viewPath = $this->pathOfModule . "view/" . $view . ".php";
        $this->setView($viewPath);
    }
    
    /**
     * @return
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param $view
     */
    public function setView( $view ) {
        $this->view = $view;
    }

    /**
     * @return
     */
    public function getData( $key = NULL ) {
        if ( $key == NULL ) {
            return $this->data;
        }
        
        if ( !is_array($this->data) ) {
            return NULL;
        }

        return array_key_exists($key, $this->data) && isset($this->data[$key]) ? $this->data[$key] : NULL;
    }

    /**
     * @param $data
     */
    public function setData( $data ) {
        $this->data = $data;
    }

    public function addData( $key, $value ) {
        if ( $this->data == NULL || !is_array($this->data) ) {
            $this->data = array();
        }
        
        $this->data[$key] = $value;
    }

    /**
     * @return
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param $template
     */
    public function setTemplate( $template ) {
        $this->template = $template;
    }
    
    /**
     * @return
     */
    public function getOther() {
        return $this->other;
    }

    /**
     * @param $other
     */
    public function setOther( $other ) {
        $this->other = $other;
    }

    /**
     * 
     * @return 
     */
    public function getInnerView() {
        return $this->innerView;
    }

    /**
     * 
     * @param $innerView
     */
    public function setInnerView( $innerView ) {
        $this->innerView = $innerView;
    }
    
    /**
     * @return
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     * @param $success
     */
    public function setSuccess( $success ) {
        $_SESSION['success'] = $success;
        $this->success = $success;
    }

    /**
     * @return
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return
     */
    public function getMessageEscaped() {
        return addslashes($this->message);
    }

    /**
     * @param $message
     */
    public function setMessage( $message ) {
        $_SESSION['message'] = $message;
        $this->message = $message;
    }

    /**
     *
     * @return
     */
    public function getAdd2Head() {
        return $this->add2Head;
    }

    public function addAdd2Head( $headAdd ) {
        if ( $this->add2Head == NULL )
            $this->add2Head = $headAdd;
        else
            $this->add2Head .= "\n" . $headAdd;
    }

    /**
     *
     * @param $add2Head
     */
    public function setAdd2Head( $add2Head ) {
        $this->add2Head = $add2Head;
    }

    /**
     *
     * @return
     */
    public function getModulo() {
        return $this->modulo;
    }

    /**
     *
     * @param $modulo
     */
    public function setModulo( $modulo ) {
        $this->modulo = $modulo;
    }
    
    /**
     * 
     * @return 
     */
    public function getModuloIcon() {
        return $this->moduloIcon;
    }

    /**
     * 
     * @param $moduloIcon
     */
    public function setModuloIcon( $moduloIcon ) {
        $this->moduloIcon = $moduloIcon;
    }

    /**
     * 
     * @return 
     */
    public function getModuloNombre() {
        return $this->moduloNombre;
    }

    /**
     * 
     * @param $moduloNombre
     */
    public function setModuloNombre( $moduloNombre ) {
        $this->moduloNombre = $moduloNombre;
    }
    
    public function redirect( $url, $code = 301 ) {
        $this->template = 'redirect';
        $this->data['url'] = $url;
        $this->data['code'] = $code;
    }
    
    public function send500HttpStatusCode() {
        header(' ', true, 500);
    }
    
    public function send404HttpStatusCode() {
        header(' ', true, 404);
    }
   
    public function send401HttpStatusCode() {
        header(' ', true, 401);
    }
}