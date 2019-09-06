<?php
namespace modulos\grupos;


use modulos\grupos\logic\Logic;
use plataforma\ControllerBase;

class Controller extends ControllerBase
{
     public function perform()
     {

     }
     public function beforeFilter()
     {
         // TODO: Implement beforeFilter() method.
     }
     public function getTodosLosParticipantesRegistrados()
    {
        try{
            $this->dataAndView->setTemplate('json');

            (new Logic())->todosLosParticipantesPorGrupo($this->dataAndView);

        }catch (\Exception $e){
            $this->handleJsonException($e,'default',true);
        }
    }
}