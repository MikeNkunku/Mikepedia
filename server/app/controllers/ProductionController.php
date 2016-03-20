<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Status;
use Models\Production;

class ProductionController extends BaseController {
	/**
	* @param integer $productionId
	*/
	public function get($productionId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$production = Production::findFirst($productionId);
		if (!$production) {
			throw new Exception('Production instance not found', 404);
		}

		return array('code' => 200, 'content' => $production->toArray());
	}
}