<?php
/**
 * Created by PhpStorm.
 * User: freddy-Admin
 * Date: 23/02/2019
 * Time: 07:09 PM
 */

namespace modulos\user_theme;


use database\Connections;
use plataforma\ControllerBase;
use plataforma\exception\IntentionalException;
use plataforma\SysConstants;
use repository\LogDAO;
use repository\Login;
use repository\LoginDAO;

class Controller extends ControllerBase
{

    public function perform(){
        $loginModel = new Login();
        $this->dataAndView->addData('themes',$loginModel->getThemes());
    }

    public function beforeFilter(){}

    public function newTheme()
    {
        try {
            Connections::getConnection()->beginTransaction();

            $theme = $this->requestParams->fromPostInt('theme');
            $loginId = $this->requestParams->fromSession(SysConstants::SESS_PARAM_LOGIN_ID);

            $loginDao = new LoginDAO();
            $loginModel = $loginDao->findById($loginId);

            $loginModel->setUserTheme($theme);
            $loginDao->updateById($loginModel);

            $_SESSION[SysConstants::SESS_PARAM_USER_THEME]= $theme;

            $this->activateNotification('success','Tema actualizado correctamente');
            Connections::getConnection()->commit();
        }catch (IntentionalException $ie){
            Connections::getConnection()->rollBack();
            $this->activateNotification('danger',$ie->getMessage());
        }catch (\Exception $e){
            Connections::getConnection()->rollBack();
            Logger()->error($e->getMessage());
            $this->activateNotification('danger',$e->getMessage());
        }

        $this->redirect('/user_theme');

    }
}