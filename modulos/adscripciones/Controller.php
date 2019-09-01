<?php

namespace modulos\adscripciones;
use modulos\adscripciones\logic\Logic;
use plataforma\ControllerBase;
use plataforma\DataAndView;
use util\logger\Logger;

class Controller extends ControllerBase{

   /**
    * En este metodo se pone los datos en <code>DataAndView</code> <code>$dataAndView</code>
    */
   public function perform() {
      // TODO: Implement perform() method.
   }

   public function beforeFilter() {
      // TODO: Implement beforeFilter() method.
   }

   public function getAdscripciones() {
      $this->dataAndView->setTemplate('json');
      try {
         $this->dataAndView->addData(DataAndView::JSON_DATA, (new Logic())->getTodasAdscripciones());
      } catch (\Exception $e) {
         Logger::getLogger()->error($e);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array());
      }
   }
}