<?php

namespace plataforma\exception;

class RedirectException extends \Exception {

    const HTTP_HEADER_CODE_PERMANENT_REDIRECT = 303;
    const HTTP_HEADER_CODE_TEMPORARY_REDIRECT = 301;
    
    private $userMessage = NULL;

    public function __construct( $message, $code = NULL, $userMessage = NULL, $previous = NULL ) {
        parent::__construct($message, $code == NULL ? RedirectException::HTTP_HEADER_CODE_TEMPORARY_REDIRECT : $code, $previous);
        $this->userMessage = $userMessage;
    }

    /**
	 * @return the unknown_type
	 */
    public function getUserMessage() {
        return $this->userMessage;
    }

    /**
	 * @param unknown_type $userMessage
	 */
    public function setUserMessage( $userMessage ) {
        $this->userMessage = $userMessage;
    }
}