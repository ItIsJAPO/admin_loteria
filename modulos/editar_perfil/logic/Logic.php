<?php
namespace modulos\editar_perfil\logic;

use plataforma\exception\IntentionalException;
use util\uploadfile\FileUploaderHelper;
use util\validation\Validation;
use util\image\ImageResizer;
use plataforma\SysConstants;
use plataforma\DataAndView;
use util\token\TokenHelper;
use database\Connections;
use repository\UsersDAO;
use repository\LoginDAO;
use repository\Login;

class Logic {

    public function validePassword(&$requestParam, &$dataAndView) {
        $loginDao = new LoginDAO();

        $id = $requestParam->fromSession(SysConstants::SESS_PARAM_LOGIN_ID);
        $new_password = $requestParam->fromPost("password", true, '', false);
        $confirm_password = $requestParam->fromPost("confirm_password", true, '', false);

        //comparación del password de la base de datos con el que ingresa el editar_perfil
        $loginModel = $loginDao->findById($id);

            //validación de la nueva contraseña ingresada por el editar_perfil
        if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $new_password)) {

            $mensaje = array("type" => "danger", "message" => "Tu contraseña no es segura");

            $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);
        }elseif (strcmp($new_password, $confirm_password) !== 0) {

            $mensaje = array("type" => "danger", "message" => "Las contraseñas no coinciden");

              $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);

        }else{
            $password_hash = TokenHelper::generatePasswordHash($new_password);
            $loginModel->setPassword($password_hash);
            $loginDao->updateById($loginModel);

            $mensaje = array("type" => "success", "message" => "Contraseña actualizada corectamente");

            $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);
        }


    }

}