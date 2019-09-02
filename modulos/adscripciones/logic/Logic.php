<?php

namespace modulos\adscripciones\logic;

use plataforma\exception\IntentionalException;
use repository\Adscripcion;
use repository\AdscripcionDAO;
use util\token\TokenHelper;

class Logic {

   public function getAdscripciones() {
      $adscripciones = (new AdscripcionDAO())->getAdscripcionesActivos();
      $adscripcionArray = [];
      foreach ($adscripciones as $adscripcion) {
         $adscripcionArray[] = array(
             'uuid' => TokenHelper::generarTokenEncryptId($adscripcion->getId()),
             "nombre" => $adscripcion->getNombre(),
             'orden' => (int)$adscripcion->getOrden()
         );
      }
      return $adscripcionArray;
   }

   public function getTodasAdscripciones() {
      $adscripcionDAO = new AdscripcionDAO();
      $adscripciones = $adscripcionDAO->getAll();
      $adscripcionArray = [];
      foreach ($adscripciones as $adscripcion) {
         $adscripcionArray[] = array(
             'uuid' => TokenHelper::generarTokenEncryptId($adscripcion->getId()),
             "nombre" => $adscripcion->getNombre(),
             'orden' => (int)$adscripcion->getOrden(),
             'estatus' => (int)$adscripcion->getEstatus(),
         );
      }
      return $adscripcionArray;
   }

   public function actualizarNombreAdscripciones($uuid, $nombre) {
      $adscripcionDAO = new AdscripcionDAO();
      $adscripcion = $adscripcionDAO->getById($uuid);
      if (!$adscripcion)
         throw new IntentionalException(IntentionalException::NO_ENCONTRADO, "No se encuentrá las adscripción para actualizar");
      $adscripcion->setNombre($nombre);
      $adscripcionDAO->updateById($adscripcion);
   }

   public function actualizarEstadoAdscripciones($uuid) {
      $adscripcionDAO = new AdscripcionDAO();
      $adscripcion = $adscripcionDAO->getById($uuid);
      if (!$adscripcion)
         throw new IntentionalException(IntentionalException::NO_ENCONTRADO, "No se encuentrá las adscripción para actualizar");
      if ((int)$adscripcion->getEstatus() === Adscripcion::ESTATUS_ACTIVO) {
         $adscripcion->setEstatus(Adscripcion::ESTATUS_INACTIVO);
      } else {
         $adscripcion->setEstatus(Adscripcion::ESTATUS_ACTIVO);
      }
      $adscripcionDAO->updateById($adscripcion);
   }

   public function nuevaAdscripcion($nombre) {
      $adscripcionDAO = new AdscripcionDAO();
      $adscripcion = new Adscripcion();
      $adscripcion->setNombre($nombre);
      $adscripcion->setOrden((int)$adscripcionDAO->maxOrden()['max'] + 1);
      $adscripcion->setEstatus(Adscripcion::ESTATUS_ACTIVO);
      $adscripcionDAO->guardarAdscripcion($adscripcion);
   }

}