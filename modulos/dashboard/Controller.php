<?php

namespace modulos\dashboard;
use plataforma\ControllerBase;
use plataforma\DataAndView;

class Controller extends ControllerBase {

    public function perform()
    {
        $this->dataAndView->show("dashborad");
    }

    public function beforeFilter() {

    }

    public function nuevoUsuario()
    {
        $this->dataAndView->show('guardar_usuarios_nuevos');
    }
}