<?php

namespace repository;

class Users {

    const VIEW_MESSAGE = 1;
    const ALLOW_SOCIAL_MEDIA = 1;
    const NO_ALLOW_SOCIAL_MEDIA = 0;

	/** db_column */
	private $id;
    /** db_column */
    private $login_id;
    /** db_column */
    private $name;
    /** db_column */
    private $last_name;
    /** db_column */
    private $profile_picture;

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
    public function getLoginId() {
        return $this->login_id;
    }

    /**
     * @param mixed $login_id
     *
     * @return self
     */
    public function setLoginId( $login_id ) {
        $this->login_id = $login_id;
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

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     *
     * @return self
     */
    public function setLastName( $last_name ) {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture() {
        return $this->profile_picture;
    }

    /**
     * @param mixed $profile_picture
     *
     * @return self
     */
    public function setProfilePicture( $profile_picture ) {
        $this->profile_picture = $profile_picture;
        return $this;
    }

    public function getFullName() {
        $fullname = $this->name;

        if ( !empty($this->last_name) ) {
            $fullname .= ' ' . $this->last_name;
        }

        return $fullname;
    }
}