<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Personal {


	/** db_column */
	private $id;
	/** db_column */
	private $nombre;
//	/** db_column */
//	private $apellido;

	/** db_column */
	private $edad;
	/** db_column */
	private $asistencia;

	/**
	* @return mixed
	*/
	public function getId(){
		return $this->id;
	}

	/**
	* @param mixed $id
	* @return Personal
	*/
	public function setId($id){
		$this->id = $id;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getNombre(){
		return $this->nombre;
	}

	/**
	* @param mixed $nombre
	* @return Personal
	*/
	public function setNombre($nombre){
		$this->nombre = $nombre;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getEdad(){
		return $this->edad;
	}

	/**
	* @param mixed $edad
	* @return Personal
	*/
	public function setEdad($edad){
		$this->edad = $edad;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getAsistencia(){
		return $this->asistencia;
	}

	/**
	* @param mixed $asistencia
	* @return Personal
	*/
	public function setAsistencia($asistencia){
		$this->asistencia = $asistencia;
	   return $this;
	}
}