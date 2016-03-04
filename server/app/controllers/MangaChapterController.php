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

	/**
	* @param integer $mangaChapterId
	*/
	public function update($mangaChapterId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mc = MangaChapter::findFirst($mangaChapterId);
		if (!$mc) {
			throw new Exception('MangaChapter instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID attribute cannot be null', 409);
		}
		if (empty($putData->mangaId)) {
			throw new Exception('Manga ID cannot be null', 409);
		}
		if (empty($putData->number)) {
			throw new Exception('Number field must be filled', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}
		if (empty($putData->content)) {
			throw new Exception('Content field cannot be empty', 409);
		}

		$mc->beforeUpdate();
		$update = $mc->update(array(
			'status_id' => $putData->statusId,
			'summary' => $putData->summary,
			'content' => $putData->content,
			'number' => $putData->number,
			'manga_id' => $putData->mangaId
		));
		if (!$update) {
			throw new Exception('MangaChapter instance not updated', 409);
		}

		return array('code' => 200, 'content' => $mc->toArray());
	}

	/**
	* @param integer $mangaChapterId
	*/
	public function delete($mangaChapterId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mc = MangaChapter::findFirst($mangaChapterId);
		if (!$mc) {
			throw new Exception('MangaChapter instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		if ($mc->getStatusId() == $statusD->getId()) {
			throw new Exception('MangaChapter instance already deleted', 409);
		}

		$mc->beforeUpdate();
		$delete = $mc->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('MangaChapter instance not deleted', 409);
		}

		return array('code' => 204, 'content' => 'MangaChapter instance deleted');
	}
}