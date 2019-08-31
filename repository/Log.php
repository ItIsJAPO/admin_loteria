<?php

namespace repository;

class Log {

	/** db_column */
	private $identifier;
	/** db_column */
	private $user_id;
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

    private $user;

    /**
     * @return mixed
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     *
     * @return self
     */
    public function setIdentifier( $identifier ) {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     *
     * @return self
     */
    public function setUserId( $user_id ) {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParamsPost() {
        return $this->params_post;
    }

    /**
     * @param mixed $params_post
     *
     * @return self
     */
    public function setParamsPost( $params_post ) {
        $this->params_post = $params_post;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParamsGet() {
        return $this->params_get;
    }

    /**
     * @param mixed $params_get
     *
     * @return self
     */
    public function setParamsGet( $params_get ) {
        $this->params_get = $params_get;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param mixed $action
     *
     * @return self
     */
    public function setAction( $action ) {
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     *
     * @return self
     */
    public function setIp( $ip ) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return self
     */
    public function setUrl( $url ) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     *
     * @return self
     */
    public function setCreated( $created ) {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return self
     */
    public function setUser( $user ) {
        $this->user = $user;
        return $this;
    }
}