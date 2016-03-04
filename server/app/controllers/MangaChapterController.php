<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Status;
use Models\Manga;
use Models\MangaChapter;

class MangaChapterController extends BaseController {
	/**
	* @param integer $mangaChapterId
	*/
	public function get($mangaChapterId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mc = MangaChapter::findFirst($mangaChapterId);
		if (!$mc) {
			throw new Exception('MangaChapter instance not found', 404);
		}

		return array('code' => 200, 'content' => $mc->toArray());
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
			throw new Exception('Status ID attribute cannot be null', 409);
		}
		if (empty($postData->mangaId)) {
			throw new Exception('Manga ID cannot be null', 409);
		}
		if (empty($postData->number)) {
			throw new Exception('Number field must be filled', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}
		if (empty($postData->content)) {
			throw new Exception('Content field cannot be empty', 409);
		}

		$mc = new MangaChapter();
		$mc->beforeCreate();
		$create = $mc->create(array(
			'status_id' => $postData->statusId,
			'summary' => $postData->summary,
			'content' => $postData->content,
			'number' => $postData->number,
			'manga_id' => $postData->mangaId
		));
		if (!$create) {
			throw new Exception('MangaChapter instance not created', 409);
		}

		return array('code' => 201, 'content' => $mc->toArray());
	}
}