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

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->name)) {
			throw new Exception('Name field cannot be empty', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($postData->creatorId)) {
			throw new Exception('Creator ID field must be filled', 409);
		}
		if (empty($postData->hasAnime)) {
			throw new Exception('HasAnime field must be filled', 409);
		}
		if (empty($postData->year)) {
			throw new Exception('Year field must be filled', 409);
		}
		if (empty($postData->genres)) {
			throw new Exception('Genres field must at least contains one element', 409);
		}
		if (empty($postData->demographyId)) {
			throw new Exception('Demography ID cannot be empty', 409);
		}

		$manga = new Manga();
		$manga->beforeCreate();
		$create = $manga->create(array(
			'name' => $postData->name,
			'status_id' => $postData->statusId,
			'creator_id' => $postData->creatorId,
			'has_anime' => $postData->hasAnime,
			'year' => $postData->year,
			'genres' => $postData->genres,
			'demography_id' => $postData->demographyId
		));
		if (!$create) {
			throw new Exception('Manga instance not created', 409);
		}

		return array('code' => 201, 'content' => $manga->toArray());
	}

	/**
	* @param integer $mangaId
	*/
	public function update($mangaId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$manga = Manga::findFirst($mangaId);
		if (!$manga) {
			throw new Exception('Manga instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->name)) {
			throw new Exception('Name field cannot be empty', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($putData->creatorId)) {
			throw new Exception('Creator ID field must be filled', 409);
		}
		if (empty($putData->hasAnime)) {
			throw new Exception('HasAnime field must be filled', 409);
		}
		if (empty($putData->year)) {
			throw new Exception('Year field must be filled', 409);
		}
		if (empty($putData->genres)) {
			throw new Exception('Genres field must at least contains one element', 409);
		}
		if (empty($putData->demographyId)) {
			throw new Exception('Demography ID cannot be empty', 409);
		}

		$manga->beforeUpdate();
		$update = $manga->update(array(
			'name' => $putData->name,
			'status_id' => $putData->statusId,
			'creator_id' => $putData->creatorId,
			'has_anime' => $putData->hasAnime,
			'year' => $putData->year,
			'genres' => $putData->genres,
			'demography_id' => $putData->demographyId
		));
		if (!$update) {
			throw new Exception('Manga instance not updated', 409);
		}

		return array('code' => 200, 'content' => $manga->toArray());
	}
}