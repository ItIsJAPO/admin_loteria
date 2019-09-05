<?php

namespace modulos\api;

use database\Connections;
use modulos\adscripciones\logic\Logic as LogicAdscripcion;
use modulos\inscripciones\logic\Logic as LogicInscripciones;
use plataforma\ControllerBase;
use plataforma\DataAndView;
use plataforma\exception\IntentionalException;
use util\logger\Logger;
use util\token\TokenHelper;

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
      } catch (IntentionalException $ie) {
         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         $this->handleJsonException($e, "default", true);
      }
   }


   public function nuevoRegistro() {
      $this->dataAndView->setTemplate('json');
      try {
         Connections::getConnection()->beginTransaction();
         $this->dataAndView->addData(DataAndView::JSON_DATA, (new LogicInscripciones())->guardar($this->requestParams));
         Connections::getConnection()->commit();
      } catch (IntentionalException $ie) {
         Connections::getConnection()->rollBack();
         $this->handleJsonException($ie, "default",true);
      } catch (\Exception $e) {
         Connections::getConnection()->rollBack();
         $this->handleJsonException($e, "default", true);
      }


   }
}