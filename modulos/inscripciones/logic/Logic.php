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
use util\token\TokenHelper;

class Logic {

   public function guardar(&$requestParams) {
      $nombre = filter_var($requestParams->fromPost('nombre'), FILTER_SANITIZE_STRING);
      $edad = (int)filter_var($requestParams->fromPost('edad'), FILTER_SANITIZE_NUMBER_INT);
      $direccion = filter_var($requestParams->fromPost('direccion'), FILTER_SANITIZE_STRING);
      $telefono = filter_var($requestParams->fromPost('telefono'), FILTER_SANITIZE_STRING);
      $correo = filter_var($requestParams->fromPost('correo'), FILTER_SANITIZE_EMAIL);
      $esUniversitario = (int)filter_var($requestParams->fromPost('esUniversitario'), FILTER_SANITIZE_NUMBER_INT);
      $tipoUniversitario = (int)filter_var($requestParams->fromPost('tipoUniversitario'), FILTER_SANITIZE_NUMBER_INT);
      $universidadOEscuela = (int)TokenHelper::generarTokenDecryptId(filter_var($requestParams->fromPost('universidadOEscuela'), FILTER_SANITIZE_STRING));
      $numeroDePersonas = (int)filter_var($requestParams->fromPost('numeroDePersonas'), FILTER_SANITIZE_NUMBER_INT);
      $adscripciones = json_decode(filter_var($requestParams->fromPost('adscripciones'), FILTER_SANITIZE_STRING));

      if (!($esUniversitario === 1 || $esUniversitario === 2)) {
         throw new IntentionalException(0, "Respuesta inválida");
      }
//Validamos recapcha

      //Procedemos...
      $personal = new Personal();
      $personal->setNombre($nombre);
      $personal->setEdad($edad);
      $personal->setAsistencia(0);
      (new PersonalDAO())->save($personal);
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
      $participante->setIdPersonal($personal->getId());
      $participante->setIdContacto($personal->getId());
      $participante->setIdInstitucion($idInstitucion);
      (new ParticipanteDAO())->save($participante);
      Logger()->info($_POST);
      return array("sdds" => "dsafs");
   }

   private function validarFormulario($token) {
      $recaptcha_url = Config::get('recaptcha_url', 'recaptcha_config');
      $token_secret = Config::get('token_secret', 'recaptcha_config');
      $recaptcha_response = $token;
      $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $token_secret . '&response=' . $recaptcha_response);
      $recaptcha = json_decode($recaptcha);

      if ($recaptcha->success == null || $recaptcha->success == false) {
         return false;
      }

      if ($recaptcha->score >= 0.6) {
         return true;
      } else {
         return false;
      }
   }
}