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

            $this->dataAndView->show("edit_user");

        } catch ( \IntlException $ie ) {
            $this->handleException($ie, false);
        } catch ( \Exception $e ) {
            $this->handleException($e, true);
        }
    }

    public function saveNewPassword() {
        $this->dataAndView->setTemplate('json');

        try {

            Connections::getConnection()->beginTransaction();

            $logic = new Logic();

            $logic->validePassword($this->getRequestParams(), $this->dataAndView);

            Connections::getConnection()->commit();

        } catch ( IntentionalException $ie ) {
            Connections::getConnection()->rollBack();
            $this->handleJsonException($ie, array("type" => "danger", 'message' => $ie->getMessage()));
        } catch ( \Exception $e ) {
            Connections::getConnection()->rollBack();
            $this->handleJsonException($e, array("type" => "danger", 'message' => $e->getMessage()), true);
        }
    }
}