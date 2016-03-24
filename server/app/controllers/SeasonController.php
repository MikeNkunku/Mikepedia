<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Season;
use Models\Episode;
use Models\Status;

class SeasonController extends BaseController {
	/**
	 * @param integer $seasonId
	*/
	public function get($seasonId) {
		if ($this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$season = Season::findFirst($seasonId);
		if (!$season) {
			throw new Exception('Season instance not found', 404);
		}

		return array('code' => 200, 'content' => $season->toArray());
	}
}