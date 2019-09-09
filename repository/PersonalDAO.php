<?php

/**
*Powered by K-Models-Creator
*Author: 
*Date: 31/08/2019
*Time: 12:52:18
*/

namespace repository;

use database\Connections;
use database\SimpleDAO;
use modulos\grupos\logic\Logic;

class PersonalDAO extends SimpleDAO {

	/**
	*PersonalDAO construct
	*/
	public function __construct(){
		parent::__construct(new Personal());
	}

    public function getLideresDeGrupos($fechaInicio, $fechaFinal, $tipoAdscripcion, $numAcompanantes)
    {
        $sql = "SELECT
            p.id,
            p.nombre, 
            c.email,
            c.direccion,
            c.telefono,
            p.edad,
            p.asistencia,
            case i.tipo_universitario
            when 1 then 'Alumno'
            when 2 then 'Docente'
            when 3 then 'Administrativo'
            else 'No aplica'
            end as tipo_universitario,
            a.nombre as tipo_adscripcion,
            (select count(id_lider) from personal where id_lider  = p.id and id_lider is not null )  as acompanantes,
            (select group_concat(nombre separator ', ') from personal where id_lider  = p.id and id_lider is not null )  as acompanantes_nombres,
             DATE_FORMAT(pr.fecha_creacion, '%d/%m/%Y %H:%i:%s') as fecha_creacion
            FROM personal as p
            left join participante as pr on pr.id_personal = p.id
            left join contacto as c on c.id = pr.id_contacto
            left join institucion as i on i.id = pr.id_institucion 
            left join adscripcion as a on a.id = i.id_adscripcion
            where p.id_lider is null";

        if($fechaInicio != null && $fechaFinal != null) {
            $fechaInicio = (new \DateTime($fechaInicio))->format('Y-m-d H:i:s');
            $fechaFinal = (new \DateTime($fechaFinal))->format('Y-m-d H:i:s');
            $sql .= " and  pr.fecha_creacion >= '".$fechaInicio."' and  pr.fecha_creacion <= '".$fechaFinal."' ";
        }
        if($tipoAdscripcion != null) {
            $sql .= " and a.id = :adscripcion";
        }
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        if($tipoAdscripcion != null) {
            $stmt->bindParam(':adscripcion',$tipoAdscripcion);
        }
        $stmt->execute();
        $dataRegistros =  $stmt->fetchAll();
        $data = array();
        if(!empty($dataRegistros)){
            foreach ($dataRegistros as $items) {
                if ($numAcompanantes === $items['acompanantes'] || $numAcompanantes === null) {
                    $data[] = array(
                        'id' => $items['id'],
                        'nombre' => $items['nombre'],
                        'email' => $items['email'],
                        'direccion' => $items['direccion'],
                        'telefono' => $items['telefono'],
                        'edad' => $items['edad'],
                        'asistencia' => $items['asistencia'],
                        'tipo_universitario' => $items['tipo_universitario'],
                        'tipo_adscripcion' => $items['tipo_adscripcion'],
                        'acompanantes' => $items['acompanantes'],
                        'acompanantes_nombres' => $items['acompanantes_nombres'],
                        'fecha_creacion' => $items['fecha_creacion']
                    );
                }
            }
        }
        return $data;
	}


}