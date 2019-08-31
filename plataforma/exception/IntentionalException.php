<?php

namespace plataforma\exception;

class IntentionalException extends \Exception {

    const INCORRECT_DATA_ACCESS = 1;
    const REQUEST_TOKEN_EXPIRED = 2;
    const CONTROLLER_NOT_FOUND = 3;
    const PASSWORDS_NOT_MATCH = 4;
    const PASSWORD_NOT_VALID = 5;
    const EMAILS_NOT_MATCH = 6;
    const EMAIL_NOT_FOUND = 7;
    const EMAIL_NOT_VALID = 8;
    const MEDIA_NOT_FOUND = 9;
    const PAYMENT_EXPIRED = 10;
    const ACCESS_INACTIVE = 11;
    const ROLE_NOT_FOUND = 12;
    const USER_NOT_FOUND = 13;
    const TOKEN_EXPIRED = 14;
    const DOWNLOAD_ERROR=15;
    const BAD_REQUEST = 16;

	private $code_to_show;

	public function __construct( $code = 0, $message = NULL, \Exception $previous = NULL ) {
		$this->code_to_show = $code;
        parent::__construct($this->getMessageToShow($message), $code, $previous);
    }

	public function getMessageToShow( $message = NULL ) {
		if ( $message != NULL ) {
			return $message;
		}

		switch ( $this->code_to_show ) {
			case self::BAD_REQUEST:
			return "Petición inválida";
			case self::PAYMENT_EXPIRED:
			return "La sesión ha expirado";
			case self::REQUEST_TOKEN_EXPIRED:
			return "La solicitud ha expirado";
			case self::ROLE_NOT_FOUND:
			return "Rol de usuario inválido";
			case self::TOKEN_EXPIRED:
			return "Su solicitud ha expirado";
			case self::EMAIL_NOT_FOUND:
			return "No se encontró el correo";
			case self::EMAILS_NOT_MATCH:
			return "Los correos no coinciden";
			case self::USER_NOT_FOUND:
			return "No se encontró el usuario";
			case self::CONTROLLER_NOT_FOUND:
			return "No se encontró controlador";
			case self::PASSWORDS_NOT_MATCH:
			return "Las contraseñas no coinciden";
			case self::EMAIL_NOT_VALID:
			return "Debe especificar un correo válido";
			case self::INCORRECT_DATA_ACCESS:
			return "Correo ó contraseña incorrectos";
			case self::ACCESS_INACTIVE:
			return "Lo sentimos, de momento su cuenta no está activa";
			case self::PASSWORD_NOT_VALID:
			return "La contrase&ntilde;a no cumple con las especificaciones establecidas";
			case self::DOWNLOAD_ERROR:
			return "El archivo que intenta descargar ya no esta disponible actualmente";
			default:
			return "Error interno";
		}
	}
}