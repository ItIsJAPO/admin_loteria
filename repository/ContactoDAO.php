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

class ContactoDAO extends SimpleDAO {

	/**
	*ContactoDAO construct
	*/
	public function __construct(){
		parent::__construct(new Contacto());
	}

}