<?php

namespace modulos\adscripciones;

use modulos\adscripciones\logic\Logic;
use plataforma\ControllerBase;
use plataforma\DataAndView;
use plataforma\exception\IntentionalException;
use util\logger\Logger;
use util\token\TokenHelper;

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

   public function getAdscripciones() {
      $this->dataAndView->setTemplate('json');
      try {
         $this->dataAndView->addData(DataAndView::JSON_DATA, (new Logic())->getTodasAdscripciones());
      } catch (\Exception $e) {
         Logger::getLogger()->error($e);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array());
      }
   }

   public function actualizarNombre() {
      $this->dataAndView->setTemplate('json');
      try {
         $uuid = (int)TokenHelper::generarTokenDecryptId(filter_var($this->requestParams->fromPost('uuid'), FILTER_SANITIZE_STRING));
         $nombre = filter_var($this->requestParams->fromPost('nombre'), FILTER_SANITIZE_STRING);
         (new Logic())->actualizarNombreAdscripciones($uuid, $nombre);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array(
             'type' => 'success',
             'message' => "Se ha actualizado correctamente",
             'code' => 200
         ));
      } catch (IntentionalException $ie) {
         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         $this->handleJsonException($e, "default", true);
      }
   }

   public function cambiarEstado() {
      $this->dataAndView->setTemplate('json');
      try {
         $uuid = (int)TokenHelper::generarTokenDecryptId(filter_var($this->requestParams->fromPost('uuid'), FILTER_SANITIZE_STRING));
         (new Logic())->actualizarEstadoAdscripciones($uuid);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array(
             'type' => 'success',
             'message' => "Se ha actualizado correctamente",
             'code' => 200
         ));
      } catch (IntentionalException $ie) {
         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         $this->handleJsonException($e, "default", true);
      }
   }

   public function nuevaAdscripcion(){
      $this->dataAndView->setTemplate('json');
      try {
         $nombre = filter_var($this->requestParams->fromPost('nombre'), FILTER_SANITIZE_STRING);
         (new Logic())->nuevaAdscripcion($nombre);
         $this->dataAndView->addData(DataAndView::JSON_DATA, array(
             'type' => 'success',
             'message' => "Se ha guardado correctamente",
             'code' => 200
         ));
      } catch (IntentionalException $ie) {
         $this->handleJsonException($ie, "default");
      } catch (\Exception $e) {
         $this->handleJsonException($e, "default", true);
      }
   }
}