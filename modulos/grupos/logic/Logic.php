<?php


namespace modulos\grupos\logic;


use plataforma\DataAndView;
use repository\PersonalDAO;

class Logic
{
    public function todosLosParticipantesPorGrupo(&$dataAndView)
    {
        $data = (new PersonalDAO())->getLideresDeGrupos();

        $dataAndView->addData(DataAndView::JSON_DATA,array());
    }
}