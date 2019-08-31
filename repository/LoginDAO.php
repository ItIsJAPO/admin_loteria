<?php

/**
*Powered by K-Models-Creator
*Author: 
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

use database\Connections;
use database\SimpleDAO;

class LoginDAO extends SimpleDAO {

	/**
	*LoginDAO construct
	*/
	public function __construct(){
		parent::__construct(new Login());
	}
   public function findSystemAccountByEmailForLogin( $email ) {
      $status= Login::ESTATUS_ACTIVO;

      $query = 'select * from login where username = :email and status = :status';

      $statement = Connections::getConnection()->prepare($query);
      $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
      $statement->bindParam(":status", $status);
      $statement->bindParam(":email", $email);
      $statement->execute();

      return $statement->fetch();
   }
}