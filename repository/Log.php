<?php 

/**
*Powered by K-Models-Creator V1.1
*Author:  Jose Luis
*Cooperation:  Freddy Chable
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

class Log {


	/** db_column */
	private $identifier;
	/** db_column */
	private $id_login;
	/** db_column */
	private $params_post;
	/** db_column */
	private $params_get;
	/** db_column */
	private $action;
	/** db_column */
	private $ip;
	/** db_column */
	private $url;
	/** db_column */
	private $created;

	/**
	* @return mixed
	*/
	public function getIdentifier(){
		return $this->identifier;
	}

	/**
	* @param mixed $identifier
	* @return Log
	*/
	public function setIdentifier($identifier){
		$this->identifier = $identifier;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIdLogin(){
		return $this->id_login;
	}

	/**
	* @param mixed $id_login
	* @return Log
	*/
	public function setIdLogin($id_login){
		$this->id_login = $id_login;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getParamsPost(){
		return $this->params_post;
	}

	/**
	* @param mixed $params_post
	* @return Log
	*/
	public function setParamsPost($params_post){
		$this->params_post = $params_post;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getParamsGet(){
		return $this->params_get;
	}

	/**
	* @param mixed $params_get
	* @return Log
	*/
	public function setParamsGet($params_get){
		$this->params_get = $params_get;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getAction(){
		return $this->action;
	}

	/**
	* @param mixed $action
	* @return Log
	*/
	public function setAction($action){
		$this->action = $action;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getIp(){
		return $this->ip;
	}

	/**
	* @param mixed $ip
	* @return Log
	*/
	public function setIp($ip){
		$this->ip = $ip;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getUrl(){
		return $this->url;
	}

	/**
	* @param mixed $url
	* @return Log
	*/
	public function setUrl($url){
		$this->url = $url;
	   return $this;
	}


	/**
	* @return mixed
	*/
	public function getCreated(){
		return $this->created;
	}

	/**
	* @param mixed $created
	* @return Log
	*/
	public function setCreated($created){
		$this->created = $created;
	   return $this;
	}
}