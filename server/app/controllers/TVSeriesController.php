<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\BroadcastProgram;
use Models\TVSeries;
use Models\Status;

class TVSeriesController extends BaseController {
	/**
	 * @param integer $tvseriesId
	 */
	public function get($tvseriesId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$tvSeries = TVSeries::findFirst($tvseriesId);
		if (!$tvSeries) {
			throw new Exception('TVSeries instance not found', 404);
		}

		$bp = BroadcastProgram::findFirst($tvSeries->getBroadcastProgramId());
		if (!$bp) {
			throw new Exception('Parent class not retrieved', 500);
		}

		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($tvSeries->toArray(), $bpArr));
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
		if (empty($posData->name)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($posData->startDate)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($posData->summary)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}

		$tvSeries = new TVSeries();
		$bpId = $tvSeries->beforeCreate();
		if (!$bpId) {
			throw new Exception('Parent class not created', 500);
		}

		if (empty($postData->mainCast)) {
			throw new Exception('Main cast field must be filled', 409);
		}
		$create = $tvSeries->create(array(
			'broadcast_program_id' => $bpId,
			'main_cast' => $postData->mainCast
		));
		if (!$create) {
			throw new Exception('TVSeries instance not created', 500);
		}

		$bp = BroadcastProgram::findFirst($bpId);
		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 201, 'content' => array_merge($tvSeries->toArray(), $bpArr));
	}
}