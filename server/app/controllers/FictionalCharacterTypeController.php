<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\FictionalCharacterType;

class FictionalCharacterTypeController extends BaseController {
	/**
	 * @param integer $fictionalCharacterTypeId
	 */
	public function get($fictionalCharacterTypeId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$fct = FictionalCharacterType::findFirst($fictionalCharacterTypeId);
		if (!$fct) {
			throw new Exception('FictionalCharacterType not found', 404);
		}

		return array('code' => 200, 'content' => $fct->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$fct = FictionalCharacterType::findFirst(array(
			'conditions' => "name = :name:",
			'bind' => array('name' => $postData->name)
		));
		if ($fct) {
			throw new Exception('FictionalCharacterType already created', 409);
		}

		$fct = new FictionalCharacterType();
		$create = $fct->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('FictionalCharacterType not created', 409);
		}

		return array('code' => 201, 'content' => $fct->toArray());
	}

	/**
	 * @param integer $fictionalCharacterTypeId
	 */
	public function delete($fictionalCharacterTypeId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$fct = FictionalCharacterType::findFirst($fictionalCharacterTypeId);
		if (!$fct) {
			throw new Exception('FictionalCharacterType not found', 404);
		}

		if (!$fct->delete()) {
			throw new Exception('FictionalCharacterType not deleted', 409);
		}

		return array('code' => 204, 'content' => 'FictionalCharacterType deleted');
	}
}