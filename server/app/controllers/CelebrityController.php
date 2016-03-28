<?php

namespace Controllers;

use Models\PersonType;

use Phalcon\Exception;
use BaseController;
use Models\Celebrity;
use Models\Person;
use Models\Movie;
use Models\Production;
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
			throw new Exception('Celebrity instance not found', 404);
		}

		$p = Person::findFirst($c->getPersonId());
		if (!$p) {
			throw new Exception('Parent class not found', 404);
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
			throw new Exception('Status ID field must not be empty', 400);
		}
		if (empty($postData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 400);
		}
		if (empty($postData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 400);
		}
		if (empty($postData->gender)) {
			throw new Exception('Gender field must not be empty', 400);
		}
		if (empty($postData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 400);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must not be empty', 400);
		}
		if (empty($postData->biography)) {
			throw new Exception('Biography field cannot be empty', 400);
		}
		if (empty($postData->picture)) {
			throw new Exception('Picture field must be filled', 400);
		}
		if (empty($postData->earlyLife)) {
			throw new Exception('EarlyLife field must not be empty', 400);
		}

		$c = new Celebrity();
		$pId = $c->beforeCreate($postData);
		if (!$pId) {
			throw new Exception('Parent class not created', 500);
		}

		$create = $c->create(array(
			'person_id' => $pId,
			'early_life' => $postData->early_life
		));
		if (!$create) {
			throw new Exception('Celebrity instance not created', 500);
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
			throw new Exception('Status ID field must not be empty', 400);
		}
		if (empty($putData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 400);
		}
		if (empty($putData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 400);
		}
		if (empty($putData->gender)) {
			throw new Exception('Gender field must not be empty', 400);
		}
		if (empty($putData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 400);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must not be empty', 400);
		}
		if (empty($putData->biography)) {
			throw new Exception('Biography field cannot be empty', 400);
		}
		if (empty($putData->picture)) {
			throw new Exception('Picture field must be filled', 400);
		}
		if (empty($putData->earlyLife)) {
			throw new Exception('EarlyLife field must not be empty', 400);
		}

		$update = $c->beforeUpdate($putData);
		if (!$update) {
			throw new Exception('Parent class not updated', 500);
		}

		$update = $c->update(array('early_life' => $putData->earlyLife));
		if (!$update) {
			throw new Exception('Celebrity instance not updated', 500);
		}

		$p = Person::findFirst($c->getPersonId());
		$pArr = $p->toArray();
		unset($pArr['id']);

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

		$delete = $p->update(array(
			'status_id' => $statusD->getId(),
			'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
		if (!$delete) {
			throw new Exception('Celebrity not deleted', 500);
		}

		return array('code' => 204, 'content' => 'Celebrity instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$celebrities = Celebrity::query()
		->leftJoin('Models\Person', 'Models\Celebrity.person_id = p.id', 'p')
		->orderBy('p.firstname ASC, p.lastname ASC')
		->execute();
		if (!$celebrities) {
			throw new Exception('Query not executed', 500);
		}
		if ($celebrities->count() == 0) {
			return array('code' => 204, 'content' => 'No Celebrity instance found in database');
		}

		return array('code' => 200, 'content' => $celebrities->toArray());
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$celebrities = Celebrity::query()
		->leftJoin('Models\Person', 'Models\Celebrity.person_id = p.id', 'p')
		->notInWhere('status_id', $statusD->getId())
		->orderBy('p.firstname ASC, p.lastname ASC')
		->execute();
		if (!$celebrities) {
			throw new Exception('Query not executed', 500);
		}
		if ($celebrities->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Celebrity instance found');
		}

		return array('code' => 200, 'content' => $celebrities->toArray());
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
			throw new Exception('Invalid parameter', 400);
		}

		$status = Status::find(array('conditions' => 'name = :name:', 'bind' => array('name' => $statusName)));
		$celebrities = Celebrity::query()
		->leftJoin('Models\Person', 'Models\Celebrity.person_id = p.id', 'p')
		->where('status_id = :id:', array('id' => $status->getId()))
		->orderBy('p.firstname ASC, p.lastname ASC')
		->execute();
		if (!$celebrities) {
			throw new Exception('Query not executed', 500);
		}
		if ($celebrities->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Celebrity instance found');
		}

		return array('code' => 200, 'content' => $celebrities->toArray());
	}

	/**
	 * @param integer $celebrityId
	 */
	public function getMovies($celebrityId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$celebrity = Celebrity::findFirst($celebrityId);
		if (!$celebrity) {
			throw new Exception('Celebrity instance not found', 404);
		}

		$movies = Movie::find(array(
			'conditions' => 'producer_id = :id:',
			'bind' => array('id' => $celebrityId),
			'order' => 'release_date DESC'
		));
		if (!$movies) {
			throw new Exception('Query not executed', 500);
		}
		if ($movies->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Movie instance found');
		}

		return array('code' => 200, 'content' => $movies->toArray());
	}
}