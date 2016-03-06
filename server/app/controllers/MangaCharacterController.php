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

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field must not be empty', 409);
		}
		if (empty($postData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 409);
		}
		if (empty($postData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 409);
		}
		if (empty($postData->gender)) {
			throw new Exception('Gender field must not be empty', 409);
		}
		if (empty($postData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must not be empty', 409);
		}
		if (empty($postData->biography)) {
			throw new Exception('Biography field cannot be empty', 409);
		}
		if (empty($postData->picture)) {
			throw new Exception('Picture field must be filled', 409);
		}
		if (empty($postData->mangaId)) {
			throw new Exception('Manga ID field cannot be null', 409);
		}
		if (empty($postData->personality)) {
			throw new Exception('Personality attribute must be filled', 409);
		}

		$mc = new MangaCharacter();
		$pId = $mc->beforeCreate($postData);
		if (!$pId) {
			throw new Exception('Parent class not created', 409);
		}

		$create = $mc->create(array(
			'person_id' => $pId,
			'manga_id' => $postData->mangaId,
			'personality' => $postData->personality
		));
		if (!$create) {
			throw new Exception('MangaCharacter instance not created', 409);
		}

		$p = Person::findFirst($pId);
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 201, 'content' => array_merge($mc->toArray(), $pArr));
	}
}