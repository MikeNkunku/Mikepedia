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
}