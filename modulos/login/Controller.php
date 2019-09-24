<?php

namespace modulos\login;

use plataforma\exception\IntentionalException;

use modulos\login\logic\Logic;

use plataforma\ControllerBase;
use plataforma\SysConstants;
use plataforma\DataAndView;
use database\Connections;
use util\config\Config;
use util\token\TokenHelper;

class Controller extends ControllerBase {

   private $logic;

   public function beforeFilter() {
      $accion = $this->getRequestParams()->fromGet('acciones', false, 'perform');
      $autenticado = $this->getRequestParams()->fromSession(SysConstants::SESS_PARAM_AUTENTICADO);

      if (($autenticado === 1) && ($accion !== 'logout')) {
         $this->dataAndView->redirect('/' . Config::get("default_modulo"));
         return false;
      }

      $this->dataAndView->setTemplate('login');

      $this->dataAndView->addData('title_page_login', 'Iniciar sesion');

      $this->logic = new Logic();
   }

   public function perform() {
      try {
         $this->logic->loadViewPrincipal(
             $this->getRequestParams(),
             $this->dataAndView
         );

      } catch (IntentionalException $ie) {
         $this->handleException($ie, false, true);
      } catch (\Exception $e) {
         $this->handleException($e, true, true);
      }
   }

   public function principal() {
      try {

         $this->logic->loadViewPrincipal(
             $this->getRequestParams(),
             $this->dataAndView
         );

      } catch (IntentionalException $ie) {
         $this->handleException($ie, false, true);
      } catch (\Exception $e) {
         $this->handleException($e, true, true);
      }
   }

   public function recover() {
      try {

         $this->logic->loadViewRecover(
             $this->getRequestParams(),
             $this->dataAndView
         );

      } catch (IntentionalException $ie) {
         $this->handleException($ie, false, true);
      } catch (\Exception $e) {
         $this->handleException($e, true, true);
      }
   }

   public function forgotPassword() {
      $this->dataAndView->addData('title_page_login', 'Recuperar contraseña');
      $this->dataAndView->show('password');
   }

   public function logout() {
      session_unset();
      session_destroy();
      unset($_COOKIE['cookie_nipf']);

      $this->redirect('/');
   }

   public function login() {
      try {

          (new Logic())->verifyDataLoginUser($this->getRequestParams());

      } catch (IntentionalException $ie) {
          $_SESSION[SysConstants::SESSION_MESSAGE_ERROR]= $ie->getMessage();
      } catch (\Exception $e) {
          $_SESSION[SysConstants::SESSION_MESSAGE_ERROR]= $e->getMessage();
          Logger()->error($e->getMessage());
          Logger()->error($e->getTraceAsString());
      }
      $this->redirect('/');
   }

   public function savePassword() {
      $this->dataAndView->setTemplate('json');

      try {

         Connections::getConnection()->beginTransaction();

         $logic = new Logic();

         $logic->saveNewPassword($this->requestParams, $this->dataAndView);

         Connections::getConnection()->commit();

      } catch (IntentionalException $ie) {
         Connections::getConnection()->rollBack();

         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         Connections::getConnection()->rollBack();

         $this->handleJsonException($e, "default", true);
      }
   }

   public function sendRecover() {
      $this->dataAndView->setTemplate('json');

      try {

         Connections::getConnection()->beginTransaction();

         $logic = new Logic();
         $logic->verifyDataAndSendRecover($this->getRequestParams(), $this->dataAndView);

         Connections::getConnection()->commit();
      } catch (IntentionalException $ie) {
         Connections::getConnection()->rollBack();
         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         Connections::getConnection()->rollBack();
         $this->handleJsonException($e, "default", true);
      }
   }

   public function recoverPassword() {
      try {
         Connections::getConnection()->beginTransaction();

         $logic = new Logic();
         $logic->loadViewRecover($this->requestParams, $this->dataAndView);

         Connections::getConnection()->commit();
      } catch (IntentionalException $ie) {
         Connections::getConnection()->rollBack();
         $this->handleException($ie, false, 'error_view_login');
      } catch (\Exception $e) {
         Connections::getConnection()->rollBack();
         $this->handleException($e, true, 'error_view_login');
      }
   }
}