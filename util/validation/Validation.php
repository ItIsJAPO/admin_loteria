<?php

namespace util\validation;

use plataforma\exception\IntentionalException;

use util\token\TokenHelper;

class Validation {

	public static function validateEmail( $email, $confirm_email = '' ) {
		if ( !preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $email) ) {
			throw new IntentionalException(IntentionalException::EMAIL_NOT_VALID);
		}

		if ( !empty($confirm_email) && (strcmp($email, $confirm_email) !== 0) ) {
			throw new IntentionalException(IntentionalException::EMAILS_NOT_MATCH);
		}
	}

    public static function validateEmailAutentication( $email ) {
        if ( !preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $email) ) {
            return "false";
        } else {
        	return "true";
        }
    }
    public static function Password( $password)
    {
        if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
            throw new IntentionalException(IntentionalException::PASSWORD_NOT_VALID);
        }
        return TokenHelper::generatePasswordHash($password);
    }
    
	public static function validatePassword( $password, $confirm_password = '' ) {
		if ( !preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password) ) {
			throw new IntentionalException(IntentionalException::PASSWORD_NOT_VALID);
		}

		if ( (strcmp($password, $confirm_password) !== 0) && !empty($confirm_password) ) {
			throw new IntentionalException(IntentionalException::PASSWORDS_NOT_MATCH);
		}

		return TokenHelper::generatePasswordHash($password);
	}
}