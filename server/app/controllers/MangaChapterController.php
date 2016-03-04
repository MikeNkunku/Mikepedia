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
}