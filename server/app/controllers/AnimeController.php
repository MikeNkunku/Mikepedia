<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\BroadcastProgram;
use Models\BroadcastType;
use Models\Anime;
use Models\Episodes;
use Models\Status;

class AnimeController extends BaseController {
	/**
	 * @param integer $animeId
	 */
	public function get($animeId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$anime = Anime::findFirst($animeId);
		if (!$anime) {
			throw new Exception('Anime not found', 404);
		}

		$bp = BroadcastProgram::findFirst($anime->getBroadcastProgramId());
		if (!$bp) {
			throw new Exception('BroadcastProgram not found', 404);
		}

		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($anime->toArray(), $bpArr));
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
			throw new Exception('Type ID must not be null', 400);
		}
		if (empty($postData->name)) {
			throw new Exception('Name field must not be empty', 400);
		}
		if (empty($postData->startDate)) {
			throw new Exception('Start date field must not be empty', 400);
		}
		if (empty($postData->endDate)) {
			$postData->endDate = null;
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must not be empty', 400);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field must not be null', 400);
		}

		$anime = new Anime();
		$bpId = $anime->beforeCreate($postData);
		if (!$bpId) {
			throw new Exception('Parent class not created', 500);
		}

		if (empty($postData->mangaId)) {
			throw new Exception('Manga ID must not be null', 400);
		}
		$create = $anime->create(array(
			'broadcast_program_id' => $bpId,
			'manga_id' => $postData->mangaId
		));
		if (!$create) {
			throw new Exception('Anime instance not created', 500);
		}

		$bp = BroadcastProgram::findFirst($bpId);
		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 201, 'content' => array_merge($anime->toArray(), $bpArr));
	}

	/**
	 * @param integer $animeId
	 */
	public function delete($animeId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$anime = Anime::findFirst($animeId);
		if (!$anime) {
			throw new Exception('Anime instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$bp = BroadcastProgram::findFirst($anime->getBroadcastProgramId());
		if ($bp->getStatusId() == $statusD->getId()) {
			throw new Exception('Anime instance already deleted', 409);
		}

		$delete = $bp->update(array(
			'status_id' => $statusD->getId(),
			'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
		if (!$delete) {
			throw new Exception('Anime instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'Anime instance deleted');
	}

	/**
	 * @param integer $animeId
	 */
	public function update($animeId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$anime = Anime::findFirst($animeId);
		if (!$anime) {
			throw new Exception('Anime instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->typeId)) {
			throw new Exception('Type ID must not be null', 400);
		}
		if (empty($putData->name)) {
			throw new Exception('Name field must not be empty', 400);
		}
		if (empty($putData->startDate)) {
			throw new Exception('Start date field must not be empty', 400);
		}
		if (empty($putData->endDate)) {
			$putData->endDate = null;
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must not be empty', 400);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field must not be null', 400);
		}

		$bpId = $anime->beforeUpdate($putData);
		if (!$bpId) {
			throw new Exception('Parent class not updated', 500);
		}

		$bp = BroadcastProgram::findFirst($bpId);
		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($anime->toArray(), $bpArr));
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$animes = Anime::query()
		->leftJoin('Models\BroadcastProgram', 'Models\Anime.broadcast_program_id = bp.id', 'bp')
		->orderBy('bp.name ASC')
		->execute();
		if (!$animes) {
			throw new Exception('Query not executed', 500);
		}
		if ($animes->count() == 0) {
			return array('code' => 204, 'content' => 'No Anime instance found in database');
		}

		return array('code' => 200, 'content' => $animes->toArray());
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$animes = Anime::query()
		->leftJoin('Models\BroadcastProgram', 'Models\Anime.broadcast_program_id = bp.id', 'bp')
		->notInWhere('status_id', $statusD->getId())
		->orderBy('bp.name ASC')
		->execute();
		if (!$animes) {
			throw new Exception('Query not executed', 500);
		}
		if ($animes->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Anime instance found');
		}

		return array('code' => 200, 'content' => $animes->toArray());
	}

	/**
	 * @param text $statusName
	 */
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statuses = Status::find(array('columns' => 'name'));
		$sArr = $statuses->toArray();
		if (!in_array($statusName, $sArr)) {
			throw new Exception('Invalid parameter', 400);
		}

		$status = Status::findFirst(array('conditions' => 'name = :name:', 'bind' => array('name' => $statusName)));
		$animes = Anime::query()
		->leftJoin('Models\BroadcastProgram', 'Models\Anime.broadcast_program_id = bp.id', 'bp')
		->where('status_id = :id:', array('id' => $status->getId()))
		->orderBy('bp.name ASC')
		->execute();
		if (!$animes) {
			throw new Exception('Query not executed', 500);
		}
		if ($animes->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Anime instance found');
		}

		return array('code' => 200, 'content' => $animes->toArray());
	}
}