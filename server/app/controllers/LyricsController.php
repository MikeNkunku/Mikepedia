<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\Lyrics;

class LyricsController extends BaseController {
	/**
	* @param integer $lyricsId
	*/
	public function get($lyricsId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$lyrics = Lyrics::findFirst($lyricsId);
		if (!$lyrics) {
			throw new Exception('Lyrics not found', 404);
		}

		$lArr = $lyrics->toArray();
		$lArr['created_at'] = date('Y-m-d H:i:sP', $lArr['created_at']);
		$lArr['updated_at'] = date('Y-m-d H:i:sP', $lArr['updated_at']);

		return array('code' => 200, 'content' => $lArr);
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
		if (empty($postData->songId)) {
			throw new Exception('Lyrics must be associated with a song', 409);
		}
		if (empty($postData->content)) {
			throw new Exception('Content field must be filled', 409);
		}

		$lyrics = new Lyrics();
		$lyrics->beforeCreate();
		$create = $lyrics->create(array(
			'song_id' => $postData->songId,
			'status_id' => $postData->statusId,
			'content' => $postData->Content
		));
		if (!$create) {
			throw new Exception('Lyrics instance not created', 409);
		}

		$lArr = $lyrics->toArray();
		$lArr['created_at'] = date('Y-m-d H:i:sP', $lArr['created_at']);
		$lArr['updated_at'] = date('Y-m-d H:i:sP', $lArr['updated_at']);

		return array('code' => 201, 'content' => $lArr);
	}

	/**
	* @param integer $lyricsId
	*/
	public function update($lyricsId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($putData->songId)) {
			throw new Exception('Lyrics must be associated with a song', 409);
		}
		if (empty($putData->content)) {
			throw new Exception('Content field must be filled', 409);
		}

		$lyrics = Lyrics::findFirst($lyricsId);
		$lyrics->beforeUpdate();
		$update = $lyrics->update(array(
			'song_id' => $putData->songId,
			'status_id' => $putData->statusId,
			'content' => $putData->Content
		));
		if (!$update) {
			throw new Exception('Lyrics instance not updated', 409);
		}

		$lArr = $lyrics->toArray();
		$lArr['created_at'] = date('Y-m-d H:i:sP', $lArr['created_at']);
		$lArr['updated_at'] = date('Y-m-d H:i:sP', $lArr['updated_at']);

		return array('code' => 200, 'content' => $lArr);
	}

	/**
	* @param integer $lyricsId
	*/
	public function delete($lyricsId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$lyrics = Lyrics::findFirst($lyricsId);
		if (!$lyrics) {
			throw new Exception('Lyrics instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		if ($lyrics->getStatusId() == $statusD->getId()) {
			throw new Exception('Lyrics instance already deleted', 409);
		}

		$lyrics->beforeUpdate();
		$delete = $lyrics->update(array('status_id' => $statusD->getId()));

		if (!$delete) {
			throw new Exception('Lyrics instance not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Lyrics instance deleted');
	}
}