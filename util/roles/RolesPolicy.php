<?php

namespace util\roles;

use plataforma\SysConstants;

use repository\Login;

class RolesPolicy {

    const ROLE_ADMINISTRADOR_ID = 1;
    const ROLE_INSTRUCTOR_ID = 3;
    const ROLE_ALUMNO_ID = 2;
    
    public static function isAdmin() {
        $userRole = isset($_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID]) ? $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID] : NULL;
        return (strcasecmp(self::ROLE_ADMINISTRADOR_ID, $userRole) === 0);
    }

    public static function isAlumno() {
        $userRole = isset($_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID]) ? $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID] : NULL;
        return (strcasecmp(self::ROLE_ALUMNO_ID, $userRole) === 0);
    }

    public static function isInstructor() {
        $userRole = isset($_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID]) ? $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID] : NULL;
        return (strcasecmp(self::ROLE_INSTRUCTOR_ID, $userRole) === 0);
    }

    public static function permitted( $modulo, $accion = "perform" ) {
        $permissions = $_SESSION[SysConstants::SESS_PARAM_PERMISSIONS];
        
        $accessModulo = ( array_key_exists($modulo, $permissions) || array_key_exists('*', $permissions) );
    
        $accessAction = (
            ( array_key_exists('*', $permissions) && array_key_exists('*', $permissions['*']) ) || 
            ( array_key_exists('*', $permissions) && array_key_exists($accion, $permissions['*']) ) || 
            ( array_key_exists($modulo, $permissions) && array_key_exists('*', $permissions[$modulo]) ) || 
            ( array_key_exists($modulo, $permissions) && array_key_exists($accion, $permissions[$modulo]) )
        );
    
        $access = $accessModulo && $accessAction;
    
        return $access;
    }
}