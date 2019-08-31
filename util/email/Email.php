<?php

namespace util\email;

use util\config\Config;

use util\file\File;
use util\logger\Logger;

class Email {

    /**
     *
     * @param string $remitente
     * @param string $destino can be NULL if the parameter $destinos is set
     * @param string $asunto
     * @param string $mensaje
     * @param array $destinos
     * @param unknown $adjuntos
     * @return boolean
     */
    public function envia_email( $remitente, $destino, $asunto, $mensaje, $destinos = array(), $adjuntos = array() ) {
    	if ( ($destino == "") && (count($destinos) == 0) ) {
    		return false;
    	}

    	if ( Config::get("email_simulate", "mail_config") ) {
    		return $this->simulateMail($remitente, $destino, $asunto, $mensaje, $destinos);
    	}

    	if ( $remitente == NULL || $remitente == "" ) {
    		$remitente = Config::get("from_name", "mail_config");
    	}

    	$success = false;
        $mail = new PHPMailer(true);

    	try {

    		$body = $mensaje;

    		$mail->isSMTP();
            $mail->isHTML(true);
            $mail->msgHTML($body);
            $mail->SMTPAuth = true;
            //$mail->Timeout  = 15000;
            $mail->Subject = $asunto;
            $mail->ContentType = 'text/html';
            $mail->Timelimit  = 15000;
           // $mail->SMTPKeepAlive = true;

            $mail->Host = Config::get("host", "mail_config");
            $mail->Port = Config::get("port", "mail_config");
            $mail->CharSet = Config::get("char_set", "mail_config");
            $mail->Encoding = Config::get("encoding", "mail_config");
            $mail->Username = Config::get("username", "mail_config");
            $mail->Password = Config::get("password", "mail_config");
            $mail->WordWrap = Config::get("word_wrap", "mail_config");
            $mail->SMTPDebug = Config::get("smtp_debug", "mail_config");
            $mail->SMTPSecure = Config::get("smtp_secure", "mail_config");

    		$mail->SetFrom(Config::get("from", "mail_config"), Config::get("from_name", "mail_config"));

    		$mail->SMTPOptions = Config::get("ssl_options", "mail_config");

    		if ( $destino != NULL ) {
    			$mail->addAddress($destino);
    		}

    		foreach ( $destinos as $value ) {
    			$mail->addAddress($value);
    		}

    		foreach ( $adjuntos as $value ) {
    			$mail->addAttachment($value);
    		}
            $success = $mail->send();

    	} catch ( \Exception $e ) {
    		Logger()->error($e);
    		return false;
    	}

    	if ( !$success ) {
    		return false;
    	}

    	return true;
    }

    private function simulateMail( $remitente, $destino, $asunto, $mensaje, $destinos ) {
        Logger()->debug($destino);

        if ( $destino != NULL ) {
            $fh = fopen(Config::get("path_test_file", "mail_config") . "/" . $destino . "_" . $asunto . ".html", "a");

            fwrite($fh, $mensaje);
            fclose($fh);
        }

        Logger()->debug($destinos);

        foreach ( $destinos as $value ) {
            $fh = fopen(Config::get("path_test_file", "mail_config") . "/" . $value . "_" . $asunto . ".html", "a");

            fwrite($fh, $mensaje);
            fclose($fh);
        }

        return true;
    }
}