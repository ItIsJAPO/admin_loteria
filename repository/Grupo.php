<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Grupo {


	/** db_column */
	private $id;
	/** db_column */
	private $id_personal;
	/** db_column */
	private $id_participante;

	/**
	* @return mixed
	*/
	public function getId(){
		return $this->id;
	}

	/**
	* @param mixed $id
	* @return Grupo
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
	* @return Grupo
	*/
	public function setIdPersonal($id_personal){
		$this->id_personal = $id_personal;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIdParticipante(){
		return $this->id_participante;
	}

	/**
	* @param mixed $id_participante
	* @return Grupo
	*/
	public function setIdParticipante($id_participante){
		$this->id_participante = $id_participante;
	   return $this;
	}
}