<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\Celebrity;
use Models\Person;
use Models\Status;

class CelebrityController extends BaseController {
	/**
	 * @param integer $celebrityId
	 */
	public function get($celebrityId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$c = Celebrity::findFirst($celebrityId);
		if (!$c) {
			throw new Exception('Celebrity not found', 404);
		}

		$p = Person::findFirst($c->getPersonId());
		if (!$p) {
			throw new Exception('Parent class not found', 404);
		}

		$statusD = Status::findFirst(array('name' => 'deleted'));
		if ($p->getStatusId() == $statusD->getId()) {
			throw new Exception('Celebrity is deleted', 409);
		}

		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 200, 'content' => array_merge($c->toArray(), $pArr));
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field must not be empty', 409);
		}
		if (empty($postData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 409);
		}
		if (empty($postData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 409);
		}
		if (empty($postData->gender)) {
			throw new Exception('Gender field must not be empty', 409);
		}
		if (empty($postData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must not be empty', 409);
		}
		if (empty($postData->biography)) {
			throw new Exception('Biography field cannot be empty', 409);
		}
		if (empty($postData->picture)) {
			throw new Exception('Picture field must be filled', 409);
		}
		$c = new Celebrity();
		$pId = $c->beforeCreate($postData);
		if (!$pId) {
			throw new Exception('Parent class not created', 409);
		}

		if (empty($postData->earlyLife)) {
			throw new Exception('EarlyLife field must not be empty', 409);
		}
		$create = $c->create(array(
				'person_id' => $pId,
				'early_life' => $postData->early_life
		));
		if (!$create) {
			throw new Exception('Celebrity instance not created', 409);
		}

		$p = Person::findFirst($pId);
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 201, 'content' => array_merge($c->toArray(), $pArr));
	}

	/**
	 * @param integer $celebrityId
	 */
	public function update($celebrityId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$c = Celebrity::findFirst($celebrityId);
		if (!$c) {
			throw new Exception('Celebrity not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field must not be empty', 409);
		}
		if (empty($putData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 409);
		}
		if (empty($putData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 409);
		}
		if (empty($putData->gender)) {
			throw new Exception('Gender field must not be empty', 409);
		}
		if (empty($putData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must not be empty', 409);
		}
		if (empty($putData->biography)) {
			throw new Exception('Biography field cannot be empty', 409);
		}
		if (empty($putData->picture)) {
			throw new Exception('Picture field must be filled', 409);
		}

		$update = $c->beforeUpdate($putData);
		if (!$update) {
			throw new Exception('Parent class not updated', 409);
		}

		if (empty($putData->earlyLife)) {
			throw new Exception('EarlyLife field must not be empty', 409);
		}
		$update = $c->update(array('early_life' => $putData->earlyLife));

		if (!$update) {
			throw new Exception('Celebrity not updated', 409);
		}

		$p = Person::findFirst($c->getPersonId());
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 200, 'content' => array_merge($c->toArray, $pArr));
	}
}