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
}