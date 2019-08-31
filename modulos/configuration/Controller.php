<?php

namespace modulos\configuration;

use plataforma\exception\IntentionalException;

use modulos\configuration\logic\Logic;

use plataforma\ControllerBase;
use plataforma\DataAndView;

use util\roles\RolesPolicy;

class Controller extends ControllerBase {

	public function beforeFilter() {
		if ( !RolesPolicy::isAdmin() ) {
			$this->dataAndView->setTemplate('empty');
			$this->dataAndView->setView('error_pages/403.php');
			return false;
		}
	}

	public function perform() {
		try {

			$logic = new Logic();

			$logic->loadViewPrincipal($this->dataAndView);

		} catch( IntentionalException $ie ) {
			$this->handleException($ie);
		} catch( \Exception $e ) {
			$this->handleException($e, true);
		}
	}

	public function maintenanceMode() {
		$this->dataAndView->setTemplate('json');

		try {

			$logic = new Logic();

			$message = $logic->changeMaintenanceMode();

			$this->dataAndView->addData(DataAndView::JSON_DATA, array(
				'success' => true,
				'message' => $message
			));

		} catch( IntentionalException $ie ) {
			$this->handleJsonException($ie, "default");
		} catch( \Exception $e ) {
			$this->handleJsonException($e, "default", true);
		}
	}
}