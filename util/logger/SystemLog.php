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
		if ( array_key_exists($action, $this->actions_allowed) ) {
			try {
				
				Connections::getConnection()->beginTransaction();

				$log = new Log();
				$now = new \DateTime();
				$daoLog = new LogDAO();
				$password = TokenHelper::generatePassword();

				if ( isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ) {
				  	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				}

				$autenticado = (
		            isset($params_session[SysConstants::SESS_PARAM_AUTENTICADO]) &&
		            ($params_session[SysConstants::SESS_PARAM_AUTENTICADO] == 1)
		        );

				$log->setAction($action);
				$log->setIp($_SERVER['REMOTE_ADDR']);
				$log->setUrl($_SERVER['REQUEST_URI']);
				$log->setParamsGet(json_encode($params_get));
				$log->setCreated($now->format("Y-m-d H:i:s"));
				$log->setParamsPost(json_encode($params_post));
				$log->setIdentifier(TokenHelper::generatePasswordHash($password));

				if ( $autenticado ) {
					$log->setUserId($params_session[SysConstants::SESS_PARAM_USER_ID]);
				}

				$daoLog->save($log);

				Connections::getConnection()->commit();
			} catch ( \Exception $e ) {
				Connections::getConnection()->rollBack();

				Logger()->error($e);
			}
		}
	}
}