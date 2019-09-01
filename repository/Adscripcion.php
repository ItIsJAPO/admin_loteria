<?php

/**
 *Powered by K-Models-Creator V1.1
 *Author:  Jose Luis
 *Cooperation:  Freddy Chable
 *Date: 01/09/2019
 *Time: 01:13:10
 */

namespace repository;

class Adscripcion {
   const ESTATUS_ACTIVO = 1;

   /** db_column */
   private $id;
   /** db_column */
   private $nombre;
   /** db_column */
   private $orden;
   /** db_column */
   private $estatus;

   /**
    * @return mixed
    */
   public function getId() {
      return $this->id;
   }

   /**
    * @param mixed $id
    * @return Adscripcion
    */
   public function setId($id) {
      $this->id = $id;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getNombre() {
      return $this->nombre;
   }

   /**
    * @param mixed $nombre
    * @return Adscripcion
    */
   public function setNombre($nombre) {
      $this->nombre = $nombre;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getOrden() {
      return $this->orden;
   }

   /**
    * @param mixed $orden
    * @return Adscripcion
    */
   public function setOrden($orden) {
      $this->orden = $orden;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getEstatus() {
      return $this->estatus;
   }

   /**
    * @param mixed $estatus
    * @return Adscripcion
    */
   public function setEstatus($estatus) {
      $this->estatus = $estatus;
      return $this;
   }
}