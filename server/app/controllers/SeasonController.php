<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Season;
use Models\Episode;
use Models\Status;

class SeasonController extends BaseController {
	/**
	 * @param integer $seasonId
	*/
	public function get($seasonId) {
		if ($this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$season = Season::findFirst($seasonId);
		if (!$season) {
			throw new Exception('Season instance not found', 404);
		}

		return array('code' => 200, 'content' => $season->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->typeId)) {
			throw new Exception('Type ID field cannot be null', 409);
		}
		if (empty($postData->programId)) {
			throw new Exception('Program ID field cannot be null', 409);
		}
		if (empty($postData->number)) {
			throw new Exception('Number field must not be null', 409);
		}
		if (empty($postData->startDate)) {
			throw new Exception('Start date field must not be null', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Number field must not be null', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}

		$season = new Season();
		$season->beforeCreate();
		empty($postData->endDate) ? $season->setEndDate($postData->endDate) : $postData->setEndDate(null);
		$create = $season->create(array(
			'type_id' => $postData->typeId,
			'status_id' => $postData->statusId,
			'program_id' => $postData->programId,
			'number' => $postData->number,
			'start_date' => $postData->startDate,
			'summary' => $postData->summary
		));
		if (!$create) {
			throw new Exception('Query not executed', 500);
		}

		return array('code' => 201, 'content' => $season->toArray());
	}

	/**
	 * @param integer $seasonId
	 */
	public function update($seasonId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$season = Season::findFirst($seasonId);
		if (!$season) {
			throw new Exception('Season instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->typeId)) {
			throw new Exception('Type ID field cannot be null', 409);
		}
		if (empty($putData->programId)) {
			throw new Exception('Program ID field cannot be null', 409);
		}
		if (empty($putData->number)) {
			throw new Exception('Number field must not be null', 409);
		}
		if (empty($putData->startDate)) {
			throw new Exception('Start date field must not be null', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Number field must not be null', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}

		$season->beforeUpdate();
		empty($putData->endDate) ? $season->setEndDate($putData->endDate) : $putData->setEndDate(null);
		$update = $season->update(array(
			'type_id' => $putData->typeId,
			'status_id' => $putData->statusId,
			'program_id' => $putData->programId,
			'number' => $putData->number,
			'start_date' => $putData->startDate,
			'summary' => $putData->summary
		));
		if (!$update) {
			throw new Exception('Query not executed', 500);
		}

		return array('code' => 200, 'content' => $season->toArray());
	}

	/**
	 * @param integer $seasonId
	 */
	public function delete($seasonId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$season = Season::findFirst($seasonId);
		if (!$season) {
			throw new Exception('Season instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		if ($season->getStatusId() == $statusD->getId()) {
			throw new Exception('Season instance already deleted', 409);
		}

		$season->beforeUpdate();
		$delete = $season->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Query not executed', 500);
		}

		return array('code' => 204, 'content' => 'Season instance deleted');
	}

	public function getAll() {
		if ($this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$seasons = Season::find(array(
			'order' => 'number ASC',
			'group' => 'program_id'
		));
		if (!$seasons) {
			throw new Exception('Query not executed', 500);
		}
		if ($seasons->count() == 0) {
			return array('code' => 204, 'content' => 'No Season instance found in database');
		}

		return array('code' => 204, 'content' => $seasons->toArray());
	}
}