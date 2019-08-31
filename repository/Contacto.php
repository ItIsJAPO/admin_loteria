<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Contacto {


	/** db_column */
	private $id;
	/** db_column */
	private $email;
	/** db_column */
	private $direccion;
	/** db_column */
	private $telefono;

	/**
	* @return mixed
	*/
	public function getId(){
		return $this->id;
	}

	/**
	* @param mixed $id
	* @return Contacto
	*/
	public function setId($id){
		$this->id = $id;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getEmail(){
		return $this->email;
	}

	/**
	* @param mixed $email
	* @return Contacto
	*/
	public function setEmail($email){
		$this->email = $email;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	* @param mixed $direccion
	* @return Contacto
	*/
	public function setDireccion($direccion){
		$this->direccion = $direccion;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getTelefono(){
		return $this->telefono;
	}

	/**
	* @param mixed $telefono
	* @return Contacto
	*/
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	   return $this;
	}
}