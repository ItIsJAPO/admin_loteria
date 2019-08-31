<?php

namespace modulos\bitacora;

use plataforma\exception\IntentionalException;

use modulos\bitacora\logic\Logic;

use plataforma\ControllerBase;
use plataforma\DataAndView;

class Controller extends ControllerBase {

	public function beforeFilter() {}

	public function perform() {
		try {

			$logic = new Logic();

			$logic->loadViewPrincipal(
				$this->dataAndView
			);

		} catch( IntentionalException $ie ) {
			$this->handleException($ie);
		} catch( \Exception $e ) {
			$this->handleException($e, true);
		}
	}

	public function findByFilters() {
		$this->dataAndView->setTemplate('json');

		try {

			$logic = new Logic();

			$logs = $logic->findLogs(
				$this->getRequestParams()
			);

			$this->dataAndView->addData(DataAndView::JSON_DATA, array(
				'success' => true,
				'logs' => $logs
			));

		} catch ( IntentionalException $ie ) {
			$this->handleJsonException($ie, "default");
		} catch ( \Exception $e ) {
			$this->handleJsonException($e, "default", true);
		}
	}
}