<?php
namespace plataforma\session;

use plataforma\exception\IntentionalException;
use repository\UsersDAO;
use repository\RolesDAO;
use repository\LoginDAO;
use repository\Courses;
use repository\Login;
use plataforma\SysConstants;
use util\roles\RolesPolicy;
use plataforma\json\JSON;

class SessionLoader {

    public static function reloadSession() {
        if ( !isset($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO]) || ($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] != 1) ) {
            return;
        }

        $daoLogin = new LoginDAO();

        $login = $daoLogin->findById(
            $_SESSION[SysConstants::SESS_PARAM_LOGIN_ID]
        );

        if ( !$login ) {
            throw new IntentionalException(0);
        }

        if ( $login->isInactive() ) {
            session_unset();
            session_destroy();

            throw new IntentionalException(0, sprintf("Lo sentimos, su cuenta ha sido: %s", strtolower($login->getStatusText()))
            );
        }

        $_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] = 1;
        $_SESSION[SysConstants::SESS_PARAM_USER_THEME] = intval($login->getUserTheme());
        $_SESSION[SysConstants::SESS_PARAM_LOGIN_ID] = intval($login->getId());
        $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID] = intval($login->getRoleId());
        $_SESSION[SysConstants::SESS_PARAM_LOGIN_ESTATUS] = intval($login->getStatus());

        $daoRole = new RolesDAO();
        $daoUser = new UsersDAO();

        $role = $daoRole->findById($login->getRoleId());

        if ( !$role ) {
            throw new IntentionalException(0);
        }

        $user = $daoUser->findByField(array(
            'fieldName' => 'login_id',
            'fieldValue' => $login->getId()
        ));

        if ( !$user ) {
            throw new IntentionalException(0);
        }

        $_SESSION[SysConstants::SESS_PARAM_USER_ID] = intval($user->getId());
        $_SESSION[SysConstants::SESS_PARAM_USER_NAME] = $user->getFullName();
        $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_NAME] = $role->getName();

        if ( !$role ) {
            throw new IntentionalException(0);
        }
    }

    public static function creaSesion( $login, $remember = 0 ) {
        $_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] = 1;
        $_SESSION[SysConstants::SESS_PARAM_REMEMBER_SESSION] = $remember;
        $_SESSION[SysConstants::SESS_PARAM_SESSION_USER] = date('d/m/Y H:i:s');
        $_SESSION[SysConstants::SESS_PARAM_LOGIN_ID] = intval($login->getId());
        $_SESSION[SysConstants::SESS_PARAM_USER_THEME] = intval($login->getUserTheme());
        $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_ID] = intval($login->getRoleId());
        $_SESSION[SysConstants::SESS_PARAM_LOGIN_ESTATUS] = intval($login->getStatus());

        $daoRole = new RolesDAO();
        $daoUser = new UsersDAO();

        $role = $daoRole->findById($login->getRoleId());

        if ( !$role ) {
            throw new IntentionalException(0);
        }

        $user = $daoUser->findByField(array(
            'fieldName' => 'login_id',
            'fieldValue' => $login->getId()
        ));

        if ( !$user ) {
            throw new IntentionalException(0);
        }

        $_SESSION[SysConstants::SESS_PARAM_USER_ID] = intval($user->getId());
        $_SESSION[SysConstants::SESS_PARAM_USER_NAME] = $user->getFullName();
        $_SESSION[SysConstants::SESS_PARAM_USER_ROLE_NAME] = $role->getName();

        if ( !$role ) {
            throw new IntentionalException(0);
        }
    }

}