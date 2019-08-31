<?php

namespace repository;

class Roles {

	/** db_column */
	private $id;
	/** db_column */
	private $name;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId( $id ) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName( $name ) {
        $this->name = $name;
        return $this;
    }
}