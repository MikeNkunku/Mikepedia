<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\ProductionType;

class ProductionTypeController extends BaseController {
	/**
	 * @param integer $productionTypeId
	 */
	public function get($productionTypeId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$pt = ProductionType::findFirst($productionTypeId);
		if (!$pt) {
			throw new Exception('ProductionType not found', 404);
		}

		return array('code' => 200, 'content' => $pt->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$pt = ProductionType::findFirst(array(
			'conditions' => "name = :name:",
			'bind' => $postData->name
		));
		if ($pt) {
			throw new Exception('ProductionType already created', 409);
		}

		$pt = new ProductionType();
		$create = $pt->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('ProductionType not created', 409);
		}

		return array('code' => 201, 'content' => $pt->toArray());
	}

	/**
	 * @param integer $productionTypeId
	 */
	public function delete($productionTypeId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$pt = ProductionType::findFirst($productionTypeId);
		if (!$pt) {
			throw new Exception('ProductionType not found', 404);
		}

		if (!$pt->delete()) {
			throw new Exception('ProductionType not deleted', 409);
		}

		return array('code' => 204, 'content' => 'ProductionType deleted');
	}
}