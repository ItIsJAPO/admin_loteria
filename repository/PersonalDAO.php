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
        $sql ='
        SELECT 
        *
        FROM loteria.personal as p
        left join participante as pr on pr.id_personal = p.id
        left join institucion as i on i.id = pr.id_institucion 
        where p.id_lider is null';
        $stmt = Connections::getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
	}

}