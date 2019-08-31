<?php

namespace repository;

use database\SimpleDAO;
use database\Connections;

class LogDAO extends SimpleDAO {

	public function __construct() {
		parent::__construct(new Log());
	}

	public function findByFilters( $action, $start_date, $end_date ) {
		$query = '
			select
				log.*,
				if ( log.user_id is not null, concat_ws(" ", u.name, u.last_name), "" ) as user
			from log
				left join users u on u.id = log.user_id
			where date(log.created) between :start_date and :end_date
		';

		if ( $action != "all" ) {
			$query .= ' and log.action = :action';
		}

		$statement = Connections::getConnection()->prepare($query);
		$statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);

		if ( $action != "all" ) {
			$statement->bindParam(":action", $action);
		}

		$statement->bindParam(":start_date", $start_date);
		$statement->bindParam(":end_date", $end_date);
		$statement->execute();

		return $statement->fetchAll();
	}
}