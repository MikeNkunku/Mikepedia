<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Song;
use Models\Production;
use Models\Status;

class SongController extends BaseController {
	/**
	 * @param integer $songId
	 */
	public function get($songId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$song = Song::findFirst($songId);
		if (!$song) {
			throw new Exception('Song instance not found', 404);
		}

		return array('code' => 200, 'content' => $song->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->productionId)) {
			throw new Exception('Production ID attribute cannot be null', 409);
		}
		if (empty($postData->genreId)) {
			throw new Exception('Status ID attribute cannot be null', 409);
		}
		if (empty($postData->title)) {
			throw new Exception('Song instance must have a non-empty title', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID attribute cannot be null', 409);
		}

		$song = new Song();
		$song->beforeCreate();
		if (!empty($postData->number)) {
			$song->setNumber($postData->number);
		}
		$create = $song->create(array(
			'production_id' => $postData->productionId,
			'genre_id' => $postData->genreId,
			'title' => $postData->title,
			'status_id' => $postData->statusId
		));
		if (!$create) {
			throw new Exception('Song instance not created', 500);
		}

		return array('code' => 201, 'content' => $song->toArray());
	}

	/**
	 * @param integer $songId
	 */
	public function update($songId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$song = Song::findFirst($songId);
		if (!$song) {
			throw new Exception('Song instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->productionId)) {
			throw new Exception('Production ID attribute cannot be null', 409);
		}
		if (empty($putData->genreId)) {
			throw new Exception('Status ID attribute cannot be null', 409);
		}
		if (empty($putData->title)) {
			throw new Exception('Song instance must have a non-empty title', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID attribute cannot be null', 409);
		}

		$song->beforeUpdate();
		if (!empty($putData->number)) {
			$song->setNumber($putData->number);
		}
		$update = $song->update(array(
			'production_id' => $putData->productionId,
			'genre_id' => $putData->genreId,
			'title' => $putData->title,
			'status_id' => $putData->statusId
		));
		if (!$update) {
			throw new Exception('Song instance not updated', 500);
		}

		return array('code' => 200, 'content' => $song->toArray());
	}

	/**
	 * @param integer $songId
	 */
	public function delete($songId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$song = Song::findFirst($songId);
		if (!$song) {
			throw new Exception('Song instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		if ($song->getStatusId() == $statusD->getId()) {
			throw new Exception('Song instance already deleted', 409);
		}

		$song->beforeUpdate();
		$delete = $song->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Song instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'Song instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$songs = Song::find(array(
			'order' => 'number ASC',
			'group' => 'production_id'
		));
		if (!$songs) {
			throw new Exception('Query not executed', 500);
		}
		if ($songs->count() == 0) {
			return array('code' => 204, 'content' => 'No Song instance found in database');
		}

		return array('code' => 200, 'content' => $songs->toArray());
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$songs = Song::query()
		->notInWhere('status_id', $statusD->getId())
		->order('number ASC')
		->groupBy('production_id')
		->execute();
		if (!$songs) {
			throw new Exception('Query not executed', 500);
		}
		if ($songs->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Song instance found');
		}

		return array('code' => 200, 'content' => $songs->toArray());
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
			throw new Exception('Invalid paremeter', 409);
		}

		$status = Status::findFirst(array('conditions' => 'name = :name:', 'bind' => array('name' => $statusName)));
		$songs = Song::find(array(
			'conditions' => 'status_id = :id:',
			'bind' => array('id' => $status->getId()),
			'order' => 'number ASC',
			'group' => 'production_id'
		));
		if (!$songs) {
			throw new Exception('Query not executed', 500);
		}
		if ($songs->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Song instance found');
		}

		return array('code' => 200, 'content' => $songs->toArray());
	}
}