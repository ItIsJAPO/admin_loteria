<?php

namespace plataforma;

use plataforma\exception\PermissionException;

use plataforma\session\PermissionManager;
use plataforma\session\SessionLoader;

use util\config\Config;

class AccessManager {

    public function verifyAccess( $configUrl, &$sessParams ) {
        $autenticado = (
            isset($sessParams[SysConstants::SESS_PARAM_AUTENTICADO]) && 
            ($sessParams[SysConstants::SESS_PARAM_AUTENTICADO] == 1)
        );

        // load permissions
        $sessParams[SysConstants::SESS_PARAM_PERMISSIONS] = PermissionManager::loadPermissions($autenticado);

        $alternativa = !$autenticado ? 'login' : Config::get("default_modulo");

        $modulo = array_key_exists('modulo', $configUrl) && $configUrl['modulo'] != "" ? $configUrl['modulo'] : $alternativa;
        $accion = array_key_exists('acciones', $configUrl) && $configUrl['acciones'] != "" ? $configUrl['acciones'] : 'perform';
        
        // verificar el acceso al módulo solicitado
        $permissions = $sessParams[SysConstants::SESS_PARAM_PERMISSIONS];
        
        $accessModulo = ( array_key_exists($modulo, $permissions) || array_key_exists('*', $permissions) );
        
        $accessAction = (
            ( array_key_exists('*', $permissions) && array_key_exists('*', $permissions['*']) ) || 
            ( array_key_exists('*', $permissions) && array_key_exists($accion, $permissions['*']) ) || 
            ( array_key_exists($modulo, $permissions) && array_key_exists('*', $permissions[$modulo]) ) || 
            ( array_key_exists($modulo, $permissions) && array_key_exists($accion, $permissions[$modulo]) )
        );

        $access = $accessModulo && $accessAction;
        
        if ( !$access ) {
            throw new PermissionException('No tiene acceso a este modulo', PermissionException::NOT_ACCESS_MODULO);
        }

        return $modulo;
    }
}