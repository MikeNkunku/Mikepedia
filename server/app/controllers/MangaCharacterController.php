<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Person;
use Models\MangaCharacter;
use Models\Status;
use Models\Manga;
use Models\BroadcastProgram;
use Models\Anime;

class MangaCharacterController extends BaseController {
	/**
	* @param integer $mangaCharacterId
	*/
	public function get($mangaCharacterId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mc = MangaCharacter::findFirst($mangaCharacterId);
		if (!$mc) {
			throw new Exception('MangaCharacter instance not found', 404);
		}

		$p = Person::findFirst($mc->getPersonId());
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 200, 'content' => array_merge($mc->toArray(), $pArr));
	}
}