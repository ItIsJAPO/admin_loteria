<?php

namespace modulos\inscripciones\logic;

use plataforma\exception\IntentionalException;
use repository\Contacto;
use repository\ContactoDAO;
use repository\Institucion;
use repository\InstitucionDAO;
use repository\Participante;
use repository\ParticipanteDAO;
use repository\Personal;
use repository\PersonalDAO;
use util\config\Config;
use util\logger\Logger;
use util\token\TokenHelper;

class Logic {

   public function guardar(&$requestParams) {
      $nombre = filter_var($requestParams->fromPost('nombre'), FILTER_SANITIZE_STRING);
      $edad = (int)filter_var($requestParams->fromPost('edad'), FILTER_SANITIZE_NUMBER_INT);
      $direccion = filter_var($requestParams->fromPost('direccion'), FILTER_SANITIZE_STRING);
      $telefono = filter_var($requestParams->fromPost('telefono'), FILTER_SANITIZE_STRING);
      $correo = filter_var($requestParams->fromPost('correo'), FILTER_SANITIZE_EMAIL);
      $esUniversitario = (int)filter_var($requestParams->fromPost('esUniversitario'), FILTER_SANITIZE_NUMBER_INT);
      $tipoUniversitario = (int)filter_var($requestParams->fromPost('tipoUniversitario', false, null), FILTER_SANITIZE_NUMBER_INT);
      $universidadOEscuela = (int)TokenHelper::generarTokenDecryptId(filter_var($requestParams->fromPost('universidadOEscuela', false, null), FILTER_SANITIZE_STRING));
      $numeroDePersonas = (int)filter_var($requestParams->fromPost('numeroDePersonas'), FILTER_SANITIZE_NUMBER_INT);
      $token = $requestParams->fromPost('token',false,null,false);
      $adscripciones = json_decode($requestParams->fromPost("adscripciones", false, null, false));

      if (!($esUniversitario === 1 || $esUniversitario === 2)) {
         throw new IntentionalException(0, "Respuesta inválida");
      }
    //Validamos recapcha
      if ($this->validarFormulario($token)) {
         //Procedemos...
         $personal = new Personal();
         $personal->setNombre($nombre);
         $personal->setEdad($edad);
         $personal->setAsistencia(0);
         $personal->setIdLider(null);
         (new PersonalDAO())->save($personal);
         $idPersonal = $personal->getId();

         $contacto = new Contacto();
         $contacto->setEmail($correo);
         $contacto->setDireccion($direccion);
         $contacto->setTelefono($telefono);
         (new ContactoDAO())->save($contacto);

         $idInstitucion = null;

         if ($esUniversitario === 1) {
            if ($universidadOEscuela === 0)
               throw new IntentionalException(0, "No se encontro la institución");
            $institucion = new Institucion();
            $institucion->setIdAdscripcion($universidadOEscuela);
            $institucion->setTipoUniversitario($tipoUniversitario);
            (new InstitucionDAO())->save($institucion);
            $idInstitucion = $institucion->getId();
         }

         $participante = new Participante();
         $participante->setIdPersonal($idPersonal);
         $participante->setIdContacto($contacto->getId());
         $participante->setIdInstitucion($idInstitucion);
         (new ParticipanteDAO())->save($participante);

         if (!(empty($adscripciones))) {
            foreach ($adscripciones as $adscripcion) {
               $personal = new Personal();
               $personal->setNombre(filter_var($adscripcion->{'nombre'}, FILTER_SANITIZE_STRING));
               $personal->setEdad((int)filter_var($adscripcion->{'edad'}, FILTER_SANITIZE_NUMBER_INT));
               $personal->setAsistencia(0);
               $personal->setIdLider($idPersonal);
               (new PersonalDAO())->save($personal);
            }
         }

      } else {
         return array("type" => "danger", "message" => "Su dirección de internet se encuentra comprometida.");
      }
      return array("type" => "success", "message" => "Se ha registrado correctamente.");
   }

   private function validarFormulario($token) {
      $recaptcha_url = Config::get('recaptcha_url', 'recaptcha_config');
      $token_secret = Config::get('token_secret', 'recaptcha_config');
      $recaptcha_response = $token;
       $post_data = array(
           'secret' => $token_secret,
           'response' => $recaptcha_response
       );
       try {
           $response = self::makeRequestCallTo($recaptcha_url, true, $post_data);
       } catch ( \Exception $e ) {
           Logger()->log('google', $e->getMessage());
       }
      if (empty($response)) {
         return false;
      }

      if ($response['score'] >= 0.6) {
         return true;
      } else {
         return false;
      }
   }

    private static function makeRequestCallTo( $url, $post = true, $post_data = NULL ) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if ( $post ) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }

        $contenido = curl_exec($curl);

        $errorCode = 0;
        $errorMessage = "";

        if ( curl_errno($curl) ) {
            $errorCode = curl_errno($curl);
            $errorMessage = curl_error($curl);
        }

        curl_close($curl);

        if ( $errorCode ) {
            throw new IntentionalException($errorCode, $errorMessage);
        }

        $json_response = json_decode($contenido, true);

        $json_error = json_last_error();

        switch ( $json_error ) {
            case JSON_ERROR_NONE:
                return $json_response;
                break;
            default:
                return $contenido;
                break;
        }
    }
}