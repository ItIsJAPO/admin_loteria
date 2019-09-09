<?php


namespace modulos\grupos\logic;


use plataforma\DataAndView;
use plataforma\exception\IntentionalException;
use repository\Participante;
use repository\ParticipanteDAO;
use repository\PersonalDAO;

class Logic
{
    public function todosLosParticipantesPorGrupo(&$requestParams ,&$dataAndView)
    {
        $fechaInicio = $requestParams->fromPost('fechaInicio',false,null,false);
        $fechaFinal = $requestParams->fromPost('fechaFinal',false,null,false);
        $tipoAdscripcion = $requestParams->fromPostInt('tipoAdscripcion',false,null);
        $numAcompanantes = $requestParams->fromPostInt('numAcompanantes',false,null);
        $data = (new PersonalDAO())->getLideresDeGrupos($fechaInicio, $fechaFinal, $tipoAdscripcion, $numAcompanantes);

        $dataAndView->addData(DataAndView::JSON_DATA,$data);
    }

    public function getParticipantesPorLiderId(&$requestParams ,&$dataAndView)
    {
       $idLider =  $requestParams->fromPostInt('id_lider');
       $data = (new PersonalDAO())->findByField(array('fetchAll'=>true,'fieldName'=>'id_lider','fieldValue'=>$idLider),false);

        $dataAndView->addData(DataAndView::JSON_DATA,$data);
    }

    public function actualizarEstatusDelParticipante(&$requestParams ,&$dataAndView)
    {
        $id =  $requestParams->fromPostInt('id');

        $personalDao = new PersonalDAO();
        $personalModel = $personalDao->findById($id);

        if(empty($personalModel)){
            throw new IntentionalException(0,'No se encontro información del participante');
        }

        $asistencia =  $personalModel->getAsistencia();

        if($asistencia == Participante::SIN_ASISTENCIA){
            $message = 'La asistencia del participante fue verificada exitosamente';
            $personalModel->setAsistencia(Participante::ASISTENCIA);
        }else{
            $message = 'Le participante fue marcada como sin asistencia exitosamente';
            $personalModel->setAsistencia(Participante::SIN_ASISTENCIA);
        }
        $personalDao->updateById($personalModel);

        $dataAndView->addData(DataAndView::JSON_DATA,array('type'=>'success','message'=>$message));
    }
}