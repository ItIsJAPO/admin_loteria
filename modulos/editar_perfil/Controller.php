<?php
namespace modulos\editar_perfil;
use database\Connections;
use modulos\editar_perfil\logic\Logic;
use plataforma\ControllerBase;
use plataforma\DataAndView;
use plataforma\exception\IntentionalException;
use plataforma\SysConstants;
use repository\UsersDAO;
use util\uploadfile\FileUploaderHelper;

class Controller extends ControllerBase {

    public function beforeFilter() {}

    public function perform() {
        try {

            $userDao = new UsersDAO();

            $this->dataAndView->show("edit_user");

            $id = $this->getRequestParams()->fromSession(SysConstants::SESS_PARAM_USER_ID);

            $this->dataAndView->addData("userData", $userDao->UserGetById($id));
        } catch ( \IntlException $ie ) {
            $this->handleException($ie, false);
        } catch ( \Exception $e ) {
            $this->handleException($e, true);
        }
    }

    public function saveProfile() {
        $this->dataAndView->setTemplate('json');

        try {

            Connections::getConnection()->beginTransaction();

            $logic = new Logic();

            $logic->updatePersonalData($this->getRequestParams());

            $this->dataAndView->addData(DataAndView::JSON_DATA, array(
                "type" => "success",
                "message" => "Información actualizada correctamente",
                "estatus" => "true"
            ));

            Connections::getConnection()->commit();
        } catch ( IntentionalException $ie ) {
            Connections::getConnection()->rollBack();
            $this->handleJsonException($ie, array(
                "type" => "danger",
                'message' => $ie->getMessage()
            ));
        } catch ( \Exception $e ) {
            Connections::getConnection()->rollBack();

            $this->handleJsonException($e, array(
                "type" => "danger",
                'message' => $e->getMessage()
            ), true);
        }
    }

    public function saveNewPassword() {
        $this->dataAndView->setTemplate('json');

        try {

            Connections::getConnection()->beginTransaction();

            $logic = new Logic();

            $logic->validePassword(
                $this->getRequestParams(),
                $this->dataAndView
            );

            Connections::getConnection()->commit();

        } catch ( IntentionalException $ie ) {
            Connections::getConnection()->rollBack();

            $this->handleJsonException($ie, array(
                "type" => "danger",
                'message' => $ie->getMessage()
            ));
        } catch ( \Exception $e ) {
            Connections::getConnection()->rollBack();

            $this->handleJsonException($e, array(
                "type" => "danger",
                'message' => $e->getMessage()
            ), true);
        }
    }

    public function photoEdit() {
        $this->dataAndView->setTemplate("json");
        
        try {
            $logic= new Logic();

            $archivo = $_FILES["img_perfil"];

            $logic->isImage(
                $this->requestParams,
                $this->dataAndView,
                $archivo);

        } catch ( IntentionalException $ie ) {
            Connections::getConnection()->rollBack();
            $this->handleJsonException($ie, 'default');
        } catch ( \Exception $e ) {
            Connections::getConnection()->rollBack();
            $this->handleJsonException($e, array(
                'type' =>'warning',
                'message'=>'Algo salio mal error '.$e->getCode()), true);
        }
    }

    public function userPhoto() {
        $this->dataAndView->setTemplate("empty");

        $userDao = new UsersDAO();

        $photo = $userDao->findById($this->getRequestParams()->fromSession(SysConstants::SESS_PARAM_USER_ID));

        $absolutePath = FileUploaderHelper::pathOfPrivateDirectoryOf(FileUploaderHelper::PROFILE_PICTURES);

        if ( !empty($photo->getProfilePicture()) ) {
            $imageUrl = $absolutePath . $photo->getProfilePicture();

            if ( file_exists($imageUrl) ) {

            return FileUploaderHelper::showImage($imageUrl);

            }else{

                return FileUploaderHelper::showImage("assets/img/people.png");
            }
        }else{
            return FileUploaderHelper::showImage("assets/img/people.png");
        }
    }
}