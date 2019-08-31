<?php

namespace modulos\login\logic;

use plataforma\DataAndView;
use plataforma\exception\IntentionalException;
use plataforma\session\SessionLoader;
use util\validation\Validation;
use util\email\EmailGenerator;
use util\token\TokenHelper;
use repository\LoginDAO;
use repository\Login;

class Logic {

   public function loadViewPrincipal(&$requestParams, &$dataAndView) {
      $membership_id = $requestParams->fromGet('id', false, 0);

      $curso_text = $requestParams->fromSession('curso_text');
      $membership_text = $requestParams->fromSession('membership_text');

      $dataAndView->addData('curso_text', $curso_text);
      $dataAndView->addData('membership_id', $membership_id);
      $dataAndView->addData('membership_text', $membership_text);

      $dataAndView->show('login');
   }

   public function loadViewRecover(&$requestParams, &$dataAndView) {
      $daoLogin = new LoginDAO();

      $token = $requestParams->fromGet('token', true, '', false);
      $login_id = TokenHelper::compruebaFechaToken($token);

      Logger()->debug($token);

      if ($login_id < 0) {
         throw new IntentionalException(IntentionalException::REQUEST_TOKEN_EXPIRED);
      }

      $login = $daoLogin->findById($login_id);

      if (!$login) {
         throw new IntentionalException(IntentionalException::BAD_REQUEST);
      }

      if ($login->isInactive()) {
         throw new IntentionalException(IntentionalException::ACCESS_INACTIVE);
      }

      if ($login->getToken() == null) {
         throw new IntentionalException(IntentionalException::REQUEST_TOKEN_EXPIRED);
      }

      $dataAndView->show('recover_password');
      $dataAndView->addData("token", $login->getToken());
      $dataAndView->addData('title_page_login', 'Recuperar contraseña');
   }

   public function verifyDataLoginUser(&$requestParams) {
      $daoLogin = new LoginDAO();
      /*$login = new Login();
      $login->setRole(1);
      $login->setStatus(Login::ESTATUS_ACTIVO);
      $login->setUsername('japo@grupoicarus.com.mx');
      $login->setPassword(TokenHelper::generatePasswordHash('Dublin.Mayor'));
      $login->setCreated( date('Y-m-d H:i:s'));
      $daoLogin->save($login);*/

      Validation::validateEmail($requestParams->fromPost('email'));

      $password = $requestParams->fromPost('password', true, '', false);

      if (empty($password)) {
         throw new IntentionalException(IntentionalException::INCORRECT_DATA_ACCESS);
      }

      $login = $daoLogin->findSystemAccountByEmailForLogin($requestParams->fromPost('email'));

      if (!$login) {
         throw new IntentionalException(IntentionalException::INCORRECT_DATA_ACCESS);
      }

      if (!TokenHelper::validPasswordHash($password, $login->getPassword())) {
         throw new IntentionalException(IntentionalException::INCORRECT_DATA_ACCESS);
      }

      SessionLoader::creaSesion($login, $requestParams->fromPostInt('rememberme', false, 0));


   }

   public function saveNewPassword(&$requestParams, &$dataAndView) {
      $login_id = TokenHelper::compruebaFechaToken($requestParams->fromPost('token', true, '', false));
      $password = $requestParams->fromPost('password', true, '', false);
      $confirmPassword = $requestParams->fromPost('confirmPassword', true, '', false);

      if ($login_id < 0) {
         throw new IntentionalException(IntentionalException::REQUEST_TOKEN_EXPIRED);
      }

      $daoLogin = new LoginDAO();

      $login = $daoLogin->findById($login_id);

      if (!$login) {
         throw new IntentionalException(IntentionalException::BAD_REQUEST);
      }

      if (strlen($password) < 8) {
         throw new IntentionalException(0, 'La contraseña el minimo permitido son de 8 de caracteres.');
      }

      $password_hash = Validation::validatePassword($password, $confirmPassword);

      $login->setToken(null);
      $login->setPassword($password_hash);
      $daoLogin->updateById($login);
      $dataAndView->addData(DataAndView::JSON_DATA, array(
          'type' => 'success',
          'message' => 'Cambio de contraseña correctamente'));
   }

   public function verifyDataAndSendRecover(&$requestParams, &$dataAndView) {
      $daoLogin = new LoginDAO();

      $email = $requestParams->fromPost('email');

      Validation::validateEmail($email);

      $login = $daoLogin->findSystemAccountByEmail($email);

      if (!$login) {
         throw new IntentionalException(IntentionalException::EMAIL_NOT_FOUND);
      }

      if ($login->isInactive()) {
         throw new IntentionalException(IntentionalException::ACCESS_INACTIVE);
      }

      $token = TokenHelper::generarToken($login->getId());

      $login->setToken($token);
      $daoLogin->updateById($login);

      /**
       * Envio del correo por medio del helper
       */
      EmailGenerator::init()->sendRecoverPassword($login->getUsername(), $token);
      $dataAndView->addData(DataAndView::JSON_DATA, array('type' => 'success', 'message' => 'Se envio correo con la solicitud de recuperación de contraseña'));
   }
}