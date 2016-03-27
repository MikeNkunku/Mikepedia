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

	/**
	 * @param integer $tvseriesId
	 */
	public function update($tvseriesId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$tvSeries = TVSeries::findFirst($tvseriesId);
		if (!$tvseriesId) {
			throw new Exception('TVSeries instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->typeId)) {
			throw new Exception('Type ID field cannot be null', 409);
		}
		if (empty($putData->name)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($putData->startDate)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}

		$preUpdate = $tvSeries->beforeUpdate();
		if (!$preUpdate) {
			throw new Exception('Parent class not updated', 500);
		}

		if (empty($putData->mainCast)) {
			throw new Exception('Main cast field must be filled', 409);
		}
		$update = $tvSeries->update(array(
			'broadcast_program_id' => $putData->broadcastProgramId,
			'main_cast' => $putData->mainCast
		));
		if (!$update) {
			throw new Exception('TVSeries instance not updated', 500);
		}

		$bp = BroadcastProgram::findFirst($tvSeries->getBroadcastProgramId());
		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($tvSeries->toArray(), $bpArr));
	}
}