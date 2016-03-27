<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\BroadcastProgram;
use Models\TVSeries;
use Models\Season;
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
		if (empty($postData->name)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($postData->startDate)) {
			throw new Exception('Name field must be filled', 409);
		}
		if (empty($postData->summary)) {
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

	/**
	 * @param integer $tvseriesId
	 */
	public function delete($tvseriesId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$tvSeries = TVSeries::findFirst($tvseriesId);
		if (!$tvSeries) {
			throw new Exception('TVSeries instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$bp = BroadcastProgram::findFirst($tvSeries->getBroadcastProgramId());
		if ($bp->getStatusId() == $statusD->getId()) {
			throw new Exception('TVSeries instance already deleted', 409);
		}

		$delete = $bp->update(array(
			'status_id' => $statusD->getId(),
			'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
		if (!$delete) {
			throw new Exception('TVSeries instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'TVSeries instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$tvSeries = TVSeries::query()
		->leftJoin('Models\BroadcastProgram', 'Models\TVSeries.broadcast_program_id = bp.id', 'bp')
		->order('id ASC')
		->execute();
		if (!$tvSeries) {
			throw new Exception('Query not executed', 500);
		}
		if ($tvSeries->count() == 0) {
			return ('code' => 204, 'content' => 'No TVSeries instance found in database');
		}

		return array('code' => 200, 'content' => $tvSeries->toArray());
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$tvSeries = TVSeries::query()
		->leftJoin('Models\BroadcastProgram', 'Models\TVSeries.broadcast_program_id = bp.id', 'bp')
		->notInWhere('status_id', $statusD->getId())
		->order('id ASC')
		->execute();
		if (!$tvSeries) {
			throw new Exception('Query not executed', 500);
		}
		if ($tvSeries->count() == 0) {
			return ('code' => 204, 'content' => 'No matching TVSeries instance found');
		}

		return array('code' => 200, 'content' => $tvSeries->toArray());
	}

	/**
	 * @param text $statusName
	 */
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statuses = Status::find();
		$statusesArr = $statuses->toArray('name');
		if (!in_array($statusName, $statusesArr)) {
			throw new Exception('Invalid parameter', 409);
		}

		$status = Status::findFirst(array('conditions' => 'name = :name:', 'bind' => array('name' => $statusName)));
		$tvSeries = TVSeries::query()
		->leftJoin('Models\BroadcastProgram', 'Models\TVSeries.broadcast_program_id = bp.id', 'bp')
		->where('status_id = :id:', array('id' => $status->getId()))
		->order('name ASC')
		->execute();
		if (!$tvSeries) {
			throw new Exception('Query not executed', 500);
		}
		if ($tvSeries->count() == 0) {
			return array('code' => 204, 'content' => 'No matching TVSeries instance found');
		}

		return array('code' => 200, 'content' => $tvSeries->toArray());
	}

	/**
	 * @param integer $tvseriesId
	 */
	public function getSeasons($tvseriesId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$tvSeries = TVSeries::findFirst($tvseriesId);
		if (!$tvSeries) {
			throw new Exception('TVSeries instance not found', 404);
		}

		$seasons = Season::find(array(
			'conditions' => 'program_id = :id',
			'bind' => array('id' => $tvSeries->getBroadcastProgramId()),
			'order' => 'number ASC'
		));
		if (!$seasons) {
			throw new Exception('Query not executed', 500);
		}
		if ($seasons->count() == 0) {
			return array('code' => 204, 'content' => 'No Season instance found in database');
		}

		return array('code' => 200, 'content' => $seasons->toArray());
	}
}