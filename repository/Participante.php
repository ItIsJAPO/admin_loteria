<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Participante {

    const ASISTENCIA= 1 ;
    const SIN_ASISTENCIA = 0;

	/** db_column */
	private $id;
	/** db_column */
	private $id_personal;
	/** db_column */
	private $id_contacto;
	/** db_column */
	private $id_institucion;

	/**
	* @return mixed
	*/
	public function getId(){
		return $this->id;
	}

	/**
	* @param mixed $id
	* @return Participante
	*/
	public function setId($id){
		$this->id = $id;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIdPersonal(){
		return $this->id_personal;
	}

	/**
	* @param mixed $id_personal
	* @return Participante
	*/
	public function setIdPersonal($id_personal){
		$this->id_personal = $id_personal;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIdContacto(){
		return $this->id_contacto;
	}

	/**
	* @param mixed $id_contacto
	* @return Participante
	*/
	public function setIdContacto($id_contacto){
		$this->id_contacto = $id_contacto;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIdInstitucion(){
		return $this->id_institucion;
	}

	/**
	* @param mixed $id_institucion
	* @return Participante
	*/
	public function setIdInstitucion($id_institucion){
		$this->id_institucion = $id_institucion;
	   return $this;
	}
}