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

class PersonalDAO extends SimpleDAO {

	/**
	*PersonalDAO construct
	*/
	public function __construct(){
		parent::__construct(new Personal());
	}

    public function getLideresDeGrupos()
    {
        $sql ="SELECT
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
             DATE_FORMAT(pr.fecha_creacion, '%d/%m/%Y %H:%i:%s') as fecha_creacion
            FROM personal as p
            left join participante as pr on pr.id_personal = p.id
            left join contacto as c on c.id = pr.id_contacto
            left join institucion as i on i.id = pr.id_institucion 
            left join adscripcion as a on a.id = i.id_adscripcion
            where p.id_lider is null";
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
	}


}