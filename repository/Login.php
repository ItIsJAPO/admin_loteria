<?php

namespace repository;

class Login {

    /* estatuses */
	const ACTIVA = 1;
    const BLOQUEADA = 2;
    const ELIMINADA = 3;

    /** temas del sistema para los usuairos  **/
    const DEFAULT_THEME = 1;
    const THEME_GREEN = 2;
    const THEME_BLUE = 3;
    const THEME_PURPLE = 4;
    const THEME_ROSE = 5;
    const THEME_DARK = 6;

    /** db_column */
	private $id;
    /** db_column */
    private $username;
    /** db_column */
    private $user_theme;
    /** db_column */
    private $token;
    /** db_column */
    private $role_id;
    /** db_column */
    private $created;
    /** db_column */
    private $password;
    /** db_column */
    private $status;

    private $role;
    private $user_id;

    /**
     * @return mixed
     */
    public function getId() {
        return filter_var($this->id, FILTER_VALIDATE_INT);
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
   public function getUsername()
   {
      return $this->username;
   }

   /**
    * @param mixed $username
    * @return Login
    */
   public function setUsername($username)
   {
      $this->username = $username;
      return $this;
   }

    /**
     * @return mixed
     */
    public function getUserTheme()
    {
        return $this->user_theme;
    }

    /**
     * @param mixed $user_theme
     * @return Login
     */
    public function setUserTheme($user_theme)
    {
        $this->user_theme = $user_theme;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     *
     * @return self
     */
    public function setToken( $token ) {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoleId() {
        return filter_var($this->role_id, FILTER_VALIDATE_INT);
    }

    /**
     * @param mixed $role_id
     *
     * @return self
     */
    public function setRoleId( $role_id ) {
        $this->role_id = $role_id;
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
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword( $password ) {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return filter_var($this->status, FILTER_VALIDATE_INT);
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function setStatus( $status ) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param mixed $role
     *
     * @return self
     */
    public function setRole( $role ) {
        $this->role = $role;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return filter_var($this->user_id, FILTER_VALIDATE_INT);
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

    public function isInactive() {
        return ($this->status != self::ACTIVA);
    }

    public function getStatusText() {
        switch ( $this->status ) {
            case self::ACTIVA:
            return _("Activa");
            case self::BLOQUEADA:
            return _("Bloqueada");
            default:
            return _("Eliminada");
        }
    }
    public function getThemes(){
        $data[0]=array('id'=>self::DEFAULT_THEME,'description'=>'Tema predeterminado del sistema');
        $data[1]=array('id'=>self::THEME_GREEN,'description'=>'Verde bosque');
        $data[2]=array('id'=>self::THEME_BLUE,'description'=>'Azul cielo');
        $data[3]=array('id'=>self::THEME_PURPLE,'description'=>'Uva intenso');
        $data[4]=array('id'=>self::THEME_ROSE,'description'=>'Rosa flamingo');
        $data[5]=array('id'=>self::THEME_DARK,'description'=>'Modo nocturno');
        return $data;
    }
}