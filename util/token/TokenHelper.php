<?php

namespace util\token;

use util\logger\Logger;
use util\seguridad\Seguridad;

use util\config\Config;

class TokenHelper {

   public static function generarToken( $id ) {
      $date = new \DateTime();
      $seguridad = new Seguridad();
      $date = $date->add(new \DateInterval('PT1H'));
      $token = str_replace('+','_', $seguridad->Encrypt($id."|". $date->format("Y-m-d H:i:s")));
      return $token;
   }

   public static function generarTokenEncryptId( $id ) {
      $date = new \DateTime();
      $seguridad = new Seguridad();
      $token = $seguridad->XOREncrypt("$id|" . $date->format("s"));

      return str_replace(self::arrayValuesToReplace(), "", $token);
   }

   public static function generarTokenDecryptId( $token ) {
      $seguridad = new Seguridad();
      $tokenized = $seguridad->XORDecrypt($token);
      $datos = explode('|', $tokenized);

      return $datos[0];
   }

   public static function generarCodigo( $id ) {
      $date = new \DateTime();
      $seguridad = new Seguridad();
      $codigo = $seguridad->XOREncrypt("$id|" . $date->format("s"));

      return str_replace(self::arrayValuesToReplace(), "", $codigo);
   }

   public static function compruebaFechaToken( $token ) {
      $seguridad = new Seguridad();
      $tokenized = $seguridad->Decrypt(str_replace('_','+',$token));
      /**
       *En caso de que el token no se desencipte retornara null el resultado;
       */
      if($tokenized == null){
         return -1;
      }

      $datos = explode('|', $tokenized);
      $id = $datos[0];
      $fecha_token = strtotime($datos[1]);
      $date = new \DateTime();
      $now = $date->getTimestamp();

      if ( $now > $fecha_token ) {
         return -1;
      } else {
         return $id;
      }
   }

   public static function generatePassword() {
      $symbols = '!?#*-_/%';
      $numbers = '1234567890';
      $letters = 'aAbBcCdDeEfFgGhHkKmMnNpPqQrRtTuUvVwWxXyYzZ';

      $totalLetters = strlen($letters);
      $totalNumbers = strlen($numbers);
      $totalSymbols = strlen($symbols);

      return  $letters[mt_rand(0, $totalLetters - 1)] .
          $letters[mt_rand(0, $totalLetters - 1)] .
          $letters[mt_rand(0, $totalLetters - 1)] .
          $letters[mt_rand(0, $totalLetters - 1)] .
          $letters[mt_rand(0, $totalLetters - 1)] .
          $numbers[mt_rand(0, $totalNumbers - 1)] .
          $numbers[mt_rand(0, $totalNumbers - 1)] .
          $symbols[mt_rand(0, $totalSymbols - 1)];
   }

   public static function generatePasswordHash( $password_generated ) {
      return htmlentities(addslashes(md5(Config::get('salt_sistema') . $password_generated)));
   }

   public static function validPasswordHash( $password_generated ) {
      return htmlentities(addslashes(md5(Config::get('salt_sistema') . $password_generated)));
   }

   public static function arrayValuesToReplace() {
      return array("=", "+", "-", "%");
   }
}