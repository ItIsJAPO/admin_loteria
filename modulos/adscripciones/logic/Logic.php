<?php

namespace modulos\adscripciones\logic;

use repository\AdscripcionDAO;
use util\token\TokenHelper;

class Logic {

   public function getAdscripciones() {
      //Categoria
      $adscripcionDAO = new AdscripcionDAO();
      $adscripciones = $adscripcionDAO->getAdscripcionesActivos();
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
      //Categoria
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

}