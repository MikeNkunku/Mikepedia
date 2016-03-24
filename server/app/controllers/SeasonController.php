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
}