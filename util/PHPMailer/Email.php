<?php
/**
 * Created by PhpStorm.
 * User: José Antonio Pino Ocampo <japo@grupoicarus.com.mx>
 * Date: 2019-01-26
 * Time: 12:22
 */

namespace util\PHPMailer;


use util\config\Config;
use util\logger\Logger;

class Email {
    public function enviarEmail($remitente, $destino, $asunto, $mensaje, $destinos = array(), $adjuntos = array(), $replyTo = null) {
        $success = false;
        $mail = new PHPMailer(true);


        $host = Config::get("host", "mail_config");
        $port = Config::get("port", "mail_config");

        $username = Config::get("username", "mail_config");
        $password = Config::get("password", "mail_config");
        $levelDebug = Config::get("smtp_debug", "mail_config");


        try {
            //Server settings
            $mail->SMTPDebug = $levelDebug;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            //$mail->SMTPSecure = 'tls';
            $mail->Port = $port;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';


            $mail->setFrom($remitente,  Config::get("name_from", "mail_config"));

            if($replyTo != NULL){
               $mail->addReplyTo($replyTo);
            }

            if ($destino != NULL) {
                $mail->addAddress($destino);
            }
            foreach ($destinos as $value) {
                $mail->addAddress($value);
            }

            foreach ($adjuntos as $value) {
                $mail->addAttachment($value);
            }

            //Correo con copia para el desarrollador(es)

           foreach (Config::get("correo_dev", "sys_config") as $correo_dev){
              $mail->addBCC($correo_dev);
           }

            //Recipients
           /* $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');

            //Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');
*/
            //Content
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $success = $mail->send();
        } catch (Exception $e) {
            Logger()->error($mail->ErrorInfo);
           // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

        if (!$success)
            return false;
        return true;
    }

}