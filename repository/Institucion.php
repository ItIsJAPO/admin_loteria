<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 01/09/2019
*Time: 01:13:10
*/

namespace repository;

class Institucion {


	/** db_column */
	private $id;
	/** db_column */
	private $tipo_universitario;
	/** db_column */
	private $id_adscripcion;

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
	public function getIdAdscripcion(){
		return $this->id_adscripcion;
	}

	/**
	* @param mixed $id_adscripcion
	* @return Institucion
	*/
	public function setIdAdscripcion($id_adscripcion){
		$this->id_adscripcion = $id_adscripcion;
	   return $this;
	}
}