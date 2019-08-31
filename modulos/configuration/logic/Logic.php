<?php

namespace modulos\configuration\logic;

use plataforma\exception\IntentionalException;

use plataforma\SysConstants;

use util\roles\RolesPolicy;

use util\config\Config;

class Logic {

	private $system_config;

	public function __construct() {
		$this->system_config  = Config::get('path_sistema') . DIRECTORY_SEPARATOR . 'modulos';
		$this->system_config .= DIRECTORY_SEPARATOR . 'configuration' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'system_config.json';

		if ( !file_exists($this->system_config) ) {
			$config = array(
				'mantenimiento' => 0,
            );

			file_put_contents($this->system_config, json_encode($config));
		}
	}

	public function loadViewPrincipal( &$dataAndView ) {
		$config = json_decode(file_get_contents($this->system_config), true);

		$dataAndView->addData('mantenimiento', $config['mantenimiento']);
		$dataAndView->addData('infoTwitter', array($config['twitterMessage'],$config['twitterUrl']));
		$dataAndView->addData('infoFacebook', array($config['facebookTitle'],$config['facebookUrl']));
	}

	public function changeMaintenanceMode() {
		$config = json_decode(file_get_contents($this->system_config), true);

		if ( $config['mantenimiento'] == 0 ) {
			$config['mantenimiento'] = 1;

			$message = _("El sistema ha entrado a modo mantenimiento correctamente");
		} else {
			$config['mantenimiento'] = 0;

			$message = _("El sistema ha salido del modo mantenimiento correctamente");
		}

		file_put_contents($this->system_config, json_encode($config));

		return $message;
	}


	public function isInMaintenanceMode( $modulo ) {
		$config = json_decode(file_get_contents($this->system_config), true);

		$autenticado = (
            isset($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO]) && 
            ($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] == 1)
        );

		if ( $config['mantenimiento'] == 1 ) {
	        if ( ( !$autenticado && ($modulo == 'login') ) || RolesPolicy::isAdmin() ) {
	        	return false;
	        }

			return true;
		}

		return false;
	}
}