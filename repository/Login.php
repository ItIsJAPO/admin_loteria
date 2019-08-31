<?php

/**
 *Powered by K-Models-Creator V1.1
 *Author:  Jose Luis
 *Cooperation:  Freddy Chable
 *Date: 31/08/2019
 *Time: 12:52:18
 */

namespace repository;

class Login {

   const ESTATUS_ACTIVO = 1;

   /** db_column */
   private $id;
   /** db_column */
   private $role;
   /** db_column */
   private $created;
   /** db_column */
   private $username;
   /** db_column */
   private $password;
   /** db_column */
   private $status;

   /**
    * @return mixed
    */
   public function getId() {
      return $this->id;
   }

   /**
    * @param mixed $id
    * @return Login
    */
   public function setId($id) {
      $this->id = $id;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getRole() {
      return $this->role;
   }

   /**
    * @param mixed $role
    * @return Login
    */
   public function setRole($role) {
      $this->role = $role;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getCreated() {
      return $this->created;
   }

   /**
    * @param mixed $created
    * @return Login
    */
   public function setCreated($created) {
      $this->created = $created;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getUsername() {
      return $this->username;
   }

   /**
    * @param mixed $username
    * @return Login
    */
   public function setUsername($username) {
      $this->username = $username;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getPassword() {
      return $this->password;
   }

   /**
    * @param mixed $password
    * @return Login
    */
   public function setPassword($password) {
      $this->password = $password;
      return $this;
   }


   /**
    * @return mixed
    */
   public function getStatus() {
      return $this->status;
   }

   /**
    * @param mixed $status
    * @return Login
    */
   public function setStatus($status) {
      $this->status = $status;
      return $this;
   }
}