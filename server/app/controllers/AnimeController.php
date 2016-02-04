<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\BroadcastProgram;
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

		$statusD = Status::findFirst(array('name' => 'deleted'));
		if ($bp->getStatusId() == $statusD->getId()) {
			throw new Exception('Anime is deleted', 409);
		}

		$bpArray = $bp->toArray();
		unset($bpArra['id']);

		return array('code' => 200, 'content' => array_merge($anime->toArray(), $bpArray));
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
}