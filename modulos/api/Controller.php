<?php

namespace modulos\api;

use modulos\adscripciones\Logic\logic as LogicAdscripcion;
use plataforma\ControllerBase;
use plataforma\DataAndView;
use util\logger\Logger;

class Controller extends ControllerBase {

   public function perform() {
      $this->dataAndView->setTemplate('json');

      try {
         $info = array("status" => 'OK');
         $this->dataAndView->addData(DataAndView::JSON_DATA, $info);

      } catch (\Exception $e) {
         Logger::getLogger()->error($e);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array());
      }
   }

   public function beforeFilter() { }

   public function getAdscripciones() {
      $this->dataAndView->setTemplate('json');
      try {
         $this->dataAndView->addData(DataAndView::JSON_DATA, (new LogicAdscripcion())->getAdscripciones());
      } catch (\Exception $e) {
         Logger::getLogger()->error($e);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array());
      }
   }


}