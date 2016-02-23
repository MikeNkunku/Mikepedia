<?php

namespace Controllers;

use Models\BroadcastProgram;

use Phalcon\Exception;
use BaseController;
use Models\Episode;
use Models\Season;
use Models\BroadcastType;
use Models\Status;

class EpisodeController extends BaseController {
	/**
	 * @param integer $episodeId
	 */
	public function get($episodeId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$episode = Episode::findFirst($episodeId);
		if (!$episode) {
			throw new Exception('Episode not found', 404);
		}

		$season = Season::findFirst($episode->getSeasonId());
		$BP = BroadcastProgram::findFirst($season->getProgramId());
		$info = array('seasonNumber' => $season->getNumber(), 'programName' => $BP->getName());

		$eArr = $episode->toArray();
		$eArr['created_at'] = date('Y-m-d H:i:sP', $eArr['created_at']);
		$eArr['updated_at'] = date('Y-m-d H:i:sP', $eArr['updated_at']);

		return array('code' => 200, 'content' => array_merge($eArr, $info));
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
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($postData->seasonId)) {
			throw new Exception('Season ID cannot be null', 409);
		}
		if (empty($postData->number)) {
			throw new Exception('Number must not be empty', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field cannot be empty', 409);
		}
		if (empty($postData->description)) {
			throw new Exception('Description field cannot be empty', 409);
		}

		$episode = new Episode();
		$episode->beforeCreate();
		if (!empty($postData->airedAt)) {
			$episode->setAiredAt(date_create_from_format('Y-m-d', $postData->airedAt, new \DateTimeZone('UTC')));
		}
		$create = $episode->create(array(
				'status_id' => $postData->statusId,
				'season_id' => $postData->seasonId,
				'number' => $postData->number,
				'summary' => $postData->summary,
				'description' => $postData->description
		));

		if (!$create) {
			throw new Exception('Episode not created', 409);
		}

		$season = Season::findFirst($episode->getSeasonId());
		$seasonNumber = $season->getNumber();

		$BP = BroadcastProgram::findFirst($season->getProgramId());
		$bpName = $BP->getName();

		$eArr = $episode->toArray();
		$eArr['created_at'] = date('Y-m-d H:i:sP', $eArr['created_at']);
		$eArr['updated_at'] = date('Y-m-d H:i:sP', $eArr['updated_at']);

		return array('code' => 201, 'content' => array_merge($eArr, $seasonNumber, $bpName));
	}

	/**
	 * @param integer $episodeId
	 */
	public function update($episodeId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$episode = Episode::findFirst($episodeId);
		if (!$episode) {
			throw new Exception('Episode not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($putData->seasonId)) {
			throw new Exception('Season ID cannot be null', 409);
		}
		if (empty($putData->number)) {
			throw new Exception('Number must not be empty', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field cannot be empty', 409);
		}
		if (empty($putData->description)) {
			throw new Exception('Description field cannot be empty', 409);
		}

		$episode->beforeUpdate();
		if (!empty($putData->airedAt) && ($putData->airedAt == date('Y-m-d', $episode->getAiredAt()))) {
			$episode->setAiredAt(date_create_from_format('Y-m-d', $putData->airedAt, new \DateTimeZone('UTC')));
		}
		$update = $episode->update(array(
				'status_id' => $putData->statusId,
				'summary' => $putData->summary,
				'description' => $putData->description,
				'number' => $putData->number,
				'season_id' => $putData->seasonId
		));
		if (!$update) {
			throw new Exception('Episode not updated', 409);
		}

		$season = Season::findFirst($episode->getSeasonId());
		$seasonNumber = $season->getNumber();

		$BP = BroadcastProgram::findFirst($season->getProgramId());
		$bpName = $BP->getName();

		$eArr = $episode->toArray();
		$eArr['created_at'] = date('Y-m-d H:i:sP', $eArr['created_at']);
		$eArr['updated_at'] = date('Y-m-d H:i:sP', $eArr['updated_at']);

		return array('code' => 200, 'content' => array_merge($eArr, $seasonNumber, $bpName));
	}

	/**
	 * @param integer $episodeId
	 */
	public function delete($episodeId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$episode = Episode::findFirst($episodeId);
		if (!$episode) {
			throw new Exception('Episode not found', 404);
		}

		$statusD = Status::findFirst(array('name' => 'deleted'));
		$update = $episode->update(array(
				'status_id' => $statusD->getId(),
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
		if (!$update) {
			throw new Exception('Episode not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Episode deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$episodes = Episode::find(array('order' => 'id'));
		if ($episodes->count() == 0) {
			return array('code' => 200, 'content' => 'No episode instance in database');
		}

		$output = array();
		foreach ($episodes as $e) {
			$season = Season::findFirst($e->getSeasonId());
			$bp = BroadcastProgram::findFirst($season->getProgramId());
			$status = Status::findFirst($e->getStatusId());
			array_push($output, array(
					'id' => $e->getId(),
					'number' => $e->getNumber(),
					'status' => $status->getName(),
					'seasonNumber' => $season->getNumber(),
					'programName' => $bp->getName(),
					'createdAt' => date('Y-m-d H:i:sP', $e->getCreatedAt()),
					'updatedAt' => date('Y-m-d H:i:sP', $e->getUpdatedAt())
			));
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst(array('name' => 'deleted'));
		$episodes = Episode::query()
		->notInWhere('status_id', $statusD->getId())
		->order('id')
		->execute();
		if (!$episodes) {
			throw new Exception('Query not executed', 409);
		}

		if ($episodes->count() == 0) {
			return array('code' => 200, 'content' => 'No episode instance in database');
		}

		$output = array();
		foreach ($episodes as $e) {
			$season = Season::findFirst($e->getSeasonId());
			$bp = BroadcastProgram::findFirst($season->getProgramId());
			$status = Status::findFirst($e->getStatusId());
			array_push($output, array(
					'id' => $e->getId(),
					'number' => $e->getNumber(),
					'status' => $status->getName(),
					'seasonNumber' => $season->getNumber(),
					'programName' => $bp->getName(),
					'createdAt' => date('Y-m-d H:i:sP', $e->getCreatedAt()),
					'updatedAt' => date('Y-m-d H:i:sP', $e->getUpdatedAt())
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

		$status = Status::findFirst(array('name' => $statusName));
		$episodes = Episode::find(array(
				'status_id' => $status->getId(),
				'order' => 'id'
		));

		if ($episodes->count() == 0) {
			return array('code' => 200, 'content' => 'No matching episode found');
		}

		$output = array();
		foreach ($episodes as $e) {
			$season = Season::findFirst($e->getSeasonId());
			$bp = BroadcastProgram::findFirst($season->getProgramId());
			array_push($output, array(
					'id' => $e->getId(),
					'number' => $e->getNumber(),
					// 'status' => $status->getName(),
					'seasonNumber' => $season->getNumber(),
					'programName' => $bp->getName(),
					'createdAt' => date('Y-m-d H:i:sP', $e->getCreatedAt()),
					'updatedAt' => date('Y-m-d H:i:sP', $e->getUpdatedAt())
			));
		}

		return array('code' => 200, 'content' => $output);
	}
}