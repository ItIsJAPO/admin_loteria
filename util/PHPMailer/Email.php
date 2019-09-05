<?php
/**
 * Created by PhpStorm.
 * User: José Antonio Pino Ocampo <japo@grupoicarus.com.mx>
 * Date: 2019-01-26
 * Time: 12:22
 */

namespace util\PHPMailer;


use util\config\Config;

class Email {
   public function enviarEmail($destino, $asunto, $mensaje, $adjuntos = array()) {
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
         $mail->setFrom($username = Config::get("from", "mail_config"), 'Sistema de Registro del Museo Universitario de la Vida.');
         $mail->addReplyTo('registro@loteriauac2019.mx');
         foreach ($destino as $value)
            $mail->addAddress($value);
         foreach ($adjuntos as $value)
            $mail->addAttachment($value);
         $mail->addBCC('japo@grupoicarus.com.mx');
         $mail->isHTML();
         $mail->Subject = $asunto;
         $mail->Body = $mensaje;
         $success = $mail->send();
      } catch (Exception $e) {
         Logger()->error($e);
         Logger()->error("Error al enviar correo: " . $mail->ErrorInfo);
      }
      if (!$success)
         return false;
      return true;
   }

}