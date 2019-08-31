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

    public function isImage(&$requestParams,&$dataAndView,$file) {
        $userDao = new UsersDAO();

        $error = $file["error"];
        if ($error > 0) {
            $message = FileUploaderHelper::decodeError($error);
            $dataAndView->addData(DataAndView::JSON_DATA, array(
                "type" => "danger", "message" =>'Lo sentimos' . $message
            ));
        }

        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);

        $archivos_disp_ar = array('bmp', 'png', 'gif', 'JPEG', 'jpeg', 'svg', 'jpg', 'JPG');

        //limitamos el tamaño de la imagen
        $tamano = $file['size'];
        //tamaño maximo 3mb
        if ($tamano < 3120000) {
            //validamos la extension
            if (in_array($extension, $archivos_disp_ar, true)) {
            //                formato de imagen permitida

                $Directorio = FileUploaderHelper::pathOfPrivateDirectoryOf(FileUploaderHelper::PROFILE_PICTURES);
                FileUploaderHelper::createDirectoryIfNotExists($Directorio);

                $filename = uniqid("photo_" . date("Y-m-d_"), true);
                $filename = $filename . "." . $extension;
                $paht = $Directorio . "/" . $filename;

                $id = $requestParams->fromSession(SysConstants::SESS_PARAM_USER_ID);

                Connections::getConnection()->beginTransaction();
                //movemos la imagen de perfil al nuevo direcctorio
                 $status_img=   $this->saveMiniatura($file,$paht);
                //consultamos a imagen anterior del editar_perfil para eliminarla del server
                if ($status_img) {
                    $photo = $userDao->findById($id);
                    //insertamos en la base de datos la nueva foto de perfil del editar_perfil
                    $userDao->updatePhotoProfileUser($filename, $id);

                    $absolutePath = str_ireplace("\\", '/', $Directorio);

                    $imageUrl = $absolutePath . '/' . $photo->getProfilePicture();
                    //pasamos la ruta de la imagen para ser eliminada.
                    FileUploaderHelper::deleteFile($imageUrl);
                    $dataAndView->addData(DataAndView::JSON_DATA, array(
                        "type" => "success",
                        "message" => "Imagen de perfil actulizada correctamente"
                    ));
                }else{
                    $dataAndView->addData(DataAndView::JSON_DATA, array(
                        "type" => "danger",
                        "message" => "Error al subir archivo"));

                    }
                Connections::getConnection()->commit();


            } else {
                $dataAndView->addData(DataAndView::JSON_DATA, array(
                    "type" => "danger",
                    "message" => "El archivo no es un formato valido"));

                }

            } else {
                $dataAndView->addData(DataAndView::JSON_DATA, array(
                    "type" => "danger",
                    "message" => "El archivo es demasiado grande"));
                }
    }

    public function validePassword(&$requestParam, &$dataAndView) {
        $available = 0;
        $login = new Login();
        $loginDao = new LoginDAO();

        $id = $requestParam->fromSession(SysConstants::SESS_PARAM_LOGIN_ID);
        $new_password = $requestParam->fromPost("password", true, '', false);
        $user_password = $requestParam->fromPost("previous_password", true, '', false);
        $confirm_password = $requestParam->fromPost("confirm_password", true, '', false);

        //comparación del password de la base de datos con el que ingresa el editar_perfil
          $password_db = $loginDao->findById($id);

          $available=TokenHelper::validPasswordHash($user_password,$password_db->getPassWord());

            if($available) {
                //validación de la nueva contraseña ingresada por el editar_perfil
            if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $new_password)) {

                $mensaje = array("type" => "danger", "message" => "Tu contraseña no es segura");

                $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);
            }elseif (strcmp($new_password, $confirm_password) !== 0) {

                $mensaje = array("type" => "danger", "message" => "Las contraseñas no coinciden");

                  $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);

            }else{
                $password_hash = TokenHelper::generatePasswordHash($new_password);
                $login->setId($id);
                $login->setPassword($password_hash);
                $loginDao->updatePasswordById($login);

                $mensaje = array("type" => "success", "message" => "Información guardada corectamente", "estatus"=>"true");

                $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);
            }

            }else {

            $mensaje = array("type" => "danger", "message" => "Contraseña actual es incorrecta");

            $dataAndView->addData(DataAndView::JSON_DATA, $mensaje);
            }
    }

    public function saveMiniatura( $paramsFiles,$paht) {
        $resizer = new ImageResizer($paramsFiles['tmp_name']);

        if ( $resizer->resizeAndSaveIn($paht) ) {
            return true;
        }else{
            return false;
        }
    }

    public function updatePersonalData( &$requestParams ) {
        $daoUsers = new UsersDAO();
        $daoLogin = new LoginDAO();

        $user = $daoUsers->findById($requestParams->fromSession(SysConstants::SESS_PARAM_USER_ID));

        if ( !$user ) {
            throw new IntentionalException(IntentionalException::USER_NOT_FOUND);
        }

        $user->setName($requestParams->fromPost("names"));
        $user->setLastName($requestParams->fromPost("last_names", false, NULL));
        $daoUsers->updateById($user);

        $login = $daoLogin->findById($user->getLoginId());

        if ( !$login ) {
            throw new IntentionalException(IntentionalException::USER_NOT_FOUND);
        }
    }
}