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
}