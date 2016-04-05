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
			throw new Exception('FictionalCharacterType instance not found', 404);
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
		$fct = FictionalCharacterType::findFirst(array('conditions' => "name = :name:", 'bind' => array('name' => $postData->name)));
		if ($fct) {
			throw new Exception('FictionalCharacterType instance already created', 409);
		}

		$fct = new FictionalCharacterType();
		$create = $fct->create(array('name' => $postData->name));
		if (!$create) {
			throw new Exception('FictionalCharacterType instance not created', 500);
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
			throw new Exception('FictionalCharacterType instance not found', 404);
		}

		if (!$fct->delete()) {
			throw new Exception('FictionalCharacterType instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'FictionalCharacterType instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$FCtypes = FictionalCharacterType::find(array('order' => 'id ASC'));
		if (!$FCtypes) {
			throw new Exception('Query not executed', 500);
		}

		return array('code' => 200, 'content' => $FCtypes->toArray());
	}
}