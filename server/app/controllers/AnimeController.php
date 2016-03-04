<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\BroadcastProgram;
use Models\BroadcastType;
use Models\Anime;
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

		$statusD = Status::findFirst("name = 'deleted'"));
		if ($bp->getStatusId() == $statusD->getId()) {
			throw new Exception('Anime is deleted', 409);
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
			throw new Exception('Parent class not created', 409);
		}

		if (empty($postData->mangaId)) {
			throw new Exception('Manga ID must not be null', 400);
		}
		$create = $anime->create(array(
			'broadcast_program_id' => $bpId,
			'manga_id' => $postData->mangaId
		));
		if (!$create) {
			throw new Exception('Anime not created', 409);
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
			throw new Exception('Anime not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$bp = BroadcastProgram::findFirst($anime->getBroadcastProgramId());
		if ($bp->getStatusId() == $statusD->getId()) {
			throw new Exception('Anime already deleted', 409);
		}

		$delete = $bp->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Anime not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Anime deleted');
	}

	public function update() {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 401);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 405);
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

		$anime = Anime::findFirst($putData->id);
		$bpId = $anime->beforeUpdate($putData);
		if (!$bpId) {
			throw new Exception('Parent class not updated', 409);
		}

		$bp = BroadcastProgram::findFirst($bpId);
		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($anime->toArray(), $bpArr));
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 401);
		}

		$output = array();
		$animes = Anime::find(array('order' => 'id ASC'));
		foreach($animes as $a) {
			$bp = BroadcastProgram::findFirst($a->getBroadcastProgramId());
			$status = Status::findFirst($bp->getStatusId());
			array_push($output, array(
				'id' => $a->getId(),
				'name' => $bp->getName(),
				'status' => $status->getName(),
				'createdAt' => $bp->getCreatedAt(),
				'updatedAt' => $bp->getUpdatedAt()
			));
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 401);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$bt = BroadcastType::findFirst("name = 'anime'"));
		$parameters = array('typeId' => $bt->getId());
		$BPs = BroadcastProgram::query()
		->where('type_id = :typeId:')
		->notInWhere('status_id', $statusD->getId())
		->bind($parameters)
		->execute();
		if (!$BPs) {
			throw new Exception('Query not executed', 409);
		}

		$output = array();
		foreach($BPs as $bp) {
			$a = Anime::findFirst(array(
				'conditions' => "broadcast_program_id = ?1",
				'bind' => array(1 => $bp->getId())
			));
			$status = Status::findFirst($bp->getStatusId());
			array_push($output, array(
				'id' => $a->getId(),
				'name' => $bp->getName(),
				'status' => $status->getName(),
				'createdAt' => $bp->getCreatedAt(),
				'updatedAt' => $bp->getUpdatedAt()
			));
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param text $statusName
	 */
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 401);
		}

		$statuses = Status::find(array('columns' => 'name'));
		$sArr = $statuses->toArray();
		if (!in_array($statusName, $sArr)) {
			throw new Exception('Unvalid parameter', 409);
		}

		$output = array();
		$status = Status::findFirst(array('name' => $statusName));
		$bt = BroadcastType::findFirst(array('name' => 'anime'));
		$BPs = BroadcastProgram::find(array(
				'status_id' => $status->getId(),
				'type_id' => $bt->getId(),
				'order' => 'id ASC'
		));
		foreach($BPs as $bp) {
			$a = Anime::findFirst(array(
				'conditions' => "broadcast_program_id = ?1",
				'bind' => array(1 => $bp->getId())
			));
			$status = Status::findFirst($bp->getStatusId());
			array_push($output, array(
				'id' => $a->getId(),
				'name' => $bp->getName(),
				'status' => $status->getName(),
				'createdAt' => $bp->getCreatedAt(),
				'updatedAt' => $bp->getUpdatedAt()
			));
		}

		return array('code' => 200, 'content' => $output);
	}
}