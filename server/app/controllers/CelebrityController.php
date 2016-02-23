<?php

namespace Controllers;

use Models\PersonType;

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

		$statusD = Status::findFirst("name = 'deleted'"));
		if ($p->getStatusId() == $statusD->getId()) {
			throw new Exception('Celebrity is deleted', 409);
		}

		$pArr = $p->toArray();
		unset($pArr['id']);
		$pArr['created_at'] = date('Y-m-d H:i:sP', $pArr['created_at']);
		$pArr['updated_at'] = date('Y-m-d H:i:sP', $pArr['updated_at']);

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
		$pArr['created_at'] = date('Y-m-d H:i:sP', $pArr['created_at']);
		$pArr['updated_at'] = date('Y-m-d H:i:sP', $pArr['updated_at']);

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
		$pArr['created_at'] = date('Y-m-d H:i:sP', $pArr['created_at']);
		$pArr['updated_at'] = date('Y-m-d H:i:sP', $pArr['updated_at']);

		return array('code' => 200, 'content' => array_merge($c->toArray, $pArr));
	}

	/**
	 * @param integer $celebrityId
	 */
	public function delete($celebrityId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$p = Person::findFirst($c->getPersonId());
		$statusD = Status::findFirst("name = 'deleted'"));
		if ($p->getStatusId() == $statusD->getId()) {
			throw new Exception('Celebrity already deleted', 409);
		}

		$delete = $p->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Celebrity not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Celebrity deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$celebrities = Celebrity::find();
		$output = array();
		foreach ($celebrities as $c) {
			$p = Person::findFirst($c->getPersonId());
			$s = Status::findFirst($p->getStatusId());
			array_push($output, array(
					'id' => $c->getId(),
					'firstname' => $p->getFirstname(),
					'lastname' => $p->getLastname(),
					'status' => $s->getName(),
					'created_at' => date('Y-m-d H:i:sP', $p->getCreatedAt()),
					'updated_at' => date('Y-m-d H:i:sP', $p->getUpdatedAt())
			));
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$pt = PersonType::findFirst("name = 'Celebrity'"));
		$parameters = array(
				'typeId' => $pt->getId()
		);
		$persons = Person::query()
		->where('type_id = :typeId:')
		->notInWhere('status_id', $statusD->getId())
		->bind($parameters)
		->execute();
		if (!$persons) {
			throw new Exception('Query not executed', 409);
		}

		$output = array();
		foreach($persons as $p) {
			$c = Celebrity::findFirst(array(
				'conditions' => "person_id = :id:",
				'bind' => array('id' => $p->getId())
			));
			$status = Status::findFirst($p->getStatusId());
			array_push($output, array(
					'id' => $c->getId(),
					'firstname' => $p->getFirstname(),
					'lastname' => $p->getLastname(),
					'status' => $status->getName(),
					'createdAt' => date('Y-m-d H:i:sP', $p->getCreatedAt()),
					'updatedAt' => date('Y-m-d H:i:sP', $p->getUpdatedAt())
			));
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param text $statusName
	 */
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statuses = Status::find();
		$sArr = $statuses->toArray('name');
		if (!in_array($statusName, $sArr)) {
			throw new Exception('Invalid parameter', 409);
		}

		$celebrities = Celebrity::find();
		$output = array();
		foreach ($celebrities as $c) {
			$p = Person::findFirst($c->getPersonId());
			$s = Status::findFirst($p->getStatusId());
			if ($s->getName() == $statusName) {
				array_push($output, array(
						'id' => $c->getId(),
						'firstname' => $p->getFirstname(),
						'lastname' => $p->getLastname(),
						'created_at' => date('Y-m-d H:i:sP', $p->getCreatedAt()),
						'updated_at' => date('Y-m-d H:i:sP', $p->getUpdatedAt()),
						// 'status' => $statusName
				));
			}
		}

		return array('code' => 200, 'content' => $output);
	}
}