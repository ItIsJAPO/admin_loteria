<?php
namespace modulos\grupos;


use database\Connections;
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
    public function getParticipantesPorLiderId()
    {
        try{
            $this->dataAndView->setTemplate('json');

            (new Logic())->getParticipantesPorLiderId($this->requestParams,$this->dataAndView);

        }catch (\Exception $e){
            $this->handleJsonException($e,'default',true);
        }
    }
    public function cambiarEstatusDeParticipante()
    {
        try{
            Connections::getConnection()->beginTransaction();
            $this->dataAndView->setTemplate('json');

            (new Logic())->actualizarEstatusDelParticipante($this->requestParams,$this->dataAndView);

            Connections::getConnection()->commit();
        }catch (\Exception $e){
            Connections::getConnection()->rollBack();
            $this->handleJsonException($e,'default',true);
        }
    }


}