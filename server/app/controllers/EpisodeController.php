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
		$seasonNumber = $season->getNumber();

		$BP = BroadcastProgram::findFirst($season->getProgramId());
		$bpName = $BP->getName();

		$eArr = $episode->toArray();
		$eArr['created_at'] = date('Y-m-d H:i:sP', $eArr['created_at']);
		$eArr['updated_at'] = date('Y-m-d H:i:sP', $eArr['updated_at']);

		return array('code' => 200, 'content' => array_merge($eArr, $seasonNumber, $bpName));
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
}