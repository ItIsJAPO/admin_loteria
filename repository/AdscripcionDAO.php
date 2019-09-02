<?php

/**
 *Powered by K-Models-Creator
 *Author:
 *Date: 01/09/2019
 *Time: 01:13:10
 */

namespace repository;

use database\Connections;
use database\SimpleDAO;

class AdscripcionDAO extends SimpleDAO {

   /**
    *AdscripcionDAO construct
    */
   public function __construct() {
      parent::__construct(new Adscripcion());
   }

   public function getAdscripcionesActivos() {
      $sql = "select * from adscripcion where estatus = " . Adscripcion::ESTATUS_ACTIVO;
      $stmt = Connections::getConnection()->prepare($sql);
      $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
      $stmt->execute();
      return $stmt->fetchAll();
   }

   public function getAll() {
     return $this->findAll();
   }

   public function getById($uuid){
      return $this->findById((int)$uuid);
   }

   public function maxOrden(){
      $sql= "select max(orden) as 'max' from adscripcion";
      $stmt = Connections::getConnection()->prepare($sql);
      $stmt->setFetchMode(\PDO::FETCH_ASSOC);
      $stmt->execute();
      return $stmt->fetch();
   }
   public function guardarAdscripcion($adscripcion){
       $this->save($adscripcion);
   }

}