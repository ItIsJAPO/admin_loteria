<?php

namespace modulos\inscripciones;

use database\Connections;
use modulos\inscripciones\logic\Logic;
use plataforma\ControllerBase;

class Controller extends ControllerBase {

   /**
    * En este metodo se pone los datos en <code>DataAndView</code> <code>$dataAndView</code>
    */
   public function perform() {
      // TODO: Implement perform() method.
   }

   public function beforeFilter() {
      // TODO: Implement beforeFilter() method.
   }

   public function getAsistencia() {
      try {
         Connections::getConnection()->beginTransaction();
         $this->dataAndView->setTemplate('json');
         (new Logic())->getUsuariosRegistrados($this->dataAndView);
         Connections::getConnection()->commit();
      } catch (\Exception $e) {
         Connections::getConnection()->rollBack();
         $this->handleJsonException($e, 'default', true);
      }
   }
}