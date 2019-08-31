<?php

namespace modulos\bitacora\logic;

use plataforma\exception\IntentionalException;

use util\logger\SystemLog;

use plataforma\json\JSON;

use repository\LogDAO;
use repository\Log;

class Logic {

	public function loadViewPrincipal( &$dataAndView ) {
		$system_log = new SystemLog();

		$dataAndView->addData('acciones', $system_log->getActionsAllowed());
	}

	public function findLogs( &$requestParams ) {
		$logs = array();
		$daoLog = new LogDAO();

		$end = new \DateTime($requestParams->fromPost('end'));
		$start = new \DateTime($requestParams->fromPost('start'));

		$result = $daoLog->findByFilters(
			$requestParams->fromPost('action'), $start->format("Y-m-d"), $end->format("Y-m-d")
		);

		if ( !empty($result) ) {
			$system_log = new SystemLog();

			foreach ( $result as $log ) {
				$json_log = JSON::classToJSON($log);
				$json_log['params_get'] = json_decode($log->getParamsGet(), true);
				$json_log['params_post'] = json_decode($log->getParamsPost(), true);
				$json_log['description'] = $system_log->getActionValue($log->getAction());

				$logs[] = $json_log;
			}
		}

		return $logs;
	}
}