<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Institucion {


	/** db_column */
	private $id;
	/** db_column */
	private $tipo_universitario;
	/** db_column */
	private $adscrito;

	/**
	* @return mixed
	*/
	public function getId(){
		return $this->id;
	}

	/**
	* @param mixed $id
	* @return Institucion
	*/
	public function setId($id){
		$this->id = $id;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getTipoUniversitario(){
		return $this->tipo_universitario;
	}

	/**
	* @param mixed $tipo_universitario
	* @return Institucion
	*/
	public function setTipoUniversitario($tipo_universitario){
		$this->tipo_universitario = $tipo_universitario;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getAdscrito(){
		return $this->adscrito;
	}

	/**
	* @param mixed $adscrito
	* @return Institucion
	*/
	public function setAdscrito($adscrito){
		$this->adscrito = $adscrito;
	   return $this;
	}
}