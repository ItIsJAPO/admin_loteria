<?php

namespace repository;

use database\Connections;
use database\SimpleDAO;

class RolesDAO extends SimpleDAO {

	public function __construct() {
		parent::__construct(new Roles());
	}
}