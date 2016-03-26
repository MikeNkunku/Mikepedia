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
}