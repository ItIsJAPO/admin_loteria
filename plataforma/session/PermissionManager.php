<?php

namespace plataforma\session;

use plataforma\SysConstants;

use util\config\Config;

class PermissionManager {

    public static function loadPermissions( $autenticado ) {
        $permissions = parse_ini_file(Config::get('permission_file'), true);
        
        $permissionsRole = array();
        
        if ( !empty($permissions) ) {
            self::setPermissions($permissions['permissions.free'], $permissionsRole);

            if ( $autenticado ) {
                self::setPermissions($permissions['permissions.all'], $permissionsRole);

                $permission_index = 'permissions.' . $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_NAME];
                
                if ( isset($permissions[$permission_index]) ) {
                    self::setPermissions($permissions[$permission_index], $permissionsRole);
                }
            }
        }

        return $permissionsRole;
    }

    public static function setPermissions( $permissions, &$permissionsRole ) {
        foreach ( $permissions as $key => $value ) {
            if ( !$value ) {
                continue;
            }

            $spl = explode('.', $key);
            $modulo = $spl[0];
            $accion = $spl[1];
            
            if ( !array_key_exists($modulo, $permissionsRole) ) {
                $permissionsRole[$modulo] = array();
            }
            
            $permissionsRole[$modulo][$accion] = 1;
        }
    }
}