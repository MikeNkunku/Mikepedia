<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Manga;
use Models\MangaGenre;
use Models\MangaPublic;

class MangaController extends BaseController {
	/**
	* @param integer $mangaId
	*/
	public function get($mangaId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$manga = Manga::findFirst($mangaId);
		if (!$manga) {
			throw new Exception('Manga instance not found', 404);
		}

		$mArr = $manga->toArray();

		$mgNames = array();
		foreach ($mArr['genres'] as $mg) {
			$mg = MangaGenre::findFirst($mg);
			array_push($mgNames, $mg->getName());
		}
		$mArr['genres'] = $mgNames;

		return array('code' => 200, 'content' => $mArr);
	}
}