<?php

namespace util\logger;

use plataforma\SysConstants;

use util\token\TokenHelper;

use database\Connections;

use util\config\Config;

use repository\LogDAO;
use repository\Log;

class SystemLog {

	private $actions_allowed;

	public function __construct() {
		$config_file_log  = Config::get('path_sistema') . DIRECTORY_SEPARATOR . 'util';
        $config_file_log .= DIRECTORY_SEPARATOR . 'logger' . DIRECTORY_SEPARATOR . 'config_logs.php';

        include $config_file_log;

        $this->actions_allowed = $action_logs;
	}

	public function getActionsAllowed() {
		return $this->actions_allowed;
	}

	public function getActionValue( $action ) {
		if ( array_key_exists($action, $this->actions_allowed) ) {
			return $this->actions_allowed[$action];
		}

		return 'Acción no registrada';
	}

	public function saveLog($params_post, $params_get, $params_session, $action ) {

	}
}