<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\Lyrics;
use Models\Song;
use Models\Celebrity;

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
}