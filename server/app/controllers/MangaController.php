<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Manga;
use Models\MangaChapter;
use Models\MangaGenre;
use Models\MangaPublic;
use Models\Status;

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
			throw new Exception('Manga instance not created', 500);
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
			throw new Exception('Manga instance not updated', 500);
		}

		return array('code' => 200, 'content' => $manga->toArray());
	}

	/**
	* @param integer $mangaId
	*/
	public function delete($mangaId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$manga = Manga::findFirst($mangaId);
		if (!$manga) {
			throw new Exception('Manga instance not found', 404);
		}

		$statusD =  Status::findFirst("name = 'deleted'");
		if ($manga->getStatusId() ==  $statusD->getId()) {
			throw new Exception('Manga instance already deleted', 409);
		}

		$manga->beforeUpdate();
		$delete = $manga->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Manga instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'Manga instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mangas = Mangas::find(array('order' => 'name ASC'));
		if (!$mangas) {
			throw new Exception('Query not executed', 500);
		}
		if ($mangas->count() == 0) {
			return array('code' => 204, 'content' => 'No Manga instance found');
		}

		$output = array();
		foreach ($mangas as $m) {
			$mArr = $manga->toArray();
			$mgNames = array();
			foreach ($mArr['genres'] as $mg) {
				$mg = MangaGenre::findFirst($mg);
				array_push($mgNames, $mg->getName());
			}
			$mArr['genres'] = $mgNames;
			array_push($output, $mArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$mangas = Manga::query()
		->notInWhere('status_id', $statusD->getId())
		->orderBy('name ASC')
		->execute();
		if (!$mangas) {
			throw new Exception('Query not executed', 500);
		}
		if ($mangas->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Manga instance found');
		}

		$output = array();
		foreach ($mangas as $m) {
			$mArr = $manga->toArray();
			$mgNames = array();
			foreach ($mArr['genres'] as $mg) {
				$mg = MangaGenre::findFirst($mg);
				array_push($mgNames, $mg->getName());
			}
			$mArr['genres'] = $mgNames;
			array_push($output, $mArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	* @param text $statusName
	*/
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statuses = Status::find();
		$sArr = $statuses->toArray('name');
		if (!in_array($statusName, $sArr)) {
			throw new Exception('Invalid parameter', 405);
		}

		$status = Status::findFirst(array('conditions' => "name = :name:", 'bind' => array('name' => $statusName)));
		$mangas = Manga::find(array(
			'conditions' => "status_id = :id:",
			'bind' => array('id' => $status->getId()),
			'order' => 'name ASC'
		));
		if (!$mangas) {
			throw new Exception('Query not executed', 500);
		}
		if ($mangas->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Manga instance found');
		}

		$output = array();
		foreach ($mangas as $m) {
			$mArr = $manga->toArray();
			$mgNames = array();
			foreach ($mArr['genres'] as $mg) {
				$mg = MangaGenre::findFirst($mg);
				array_push($mgNames, $mg->getName());
			}
			$mArr['genres'] = $mgNames;
			array_push($output, $mArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	* @param integer $mangaId
	*/
	public function getChapters($mangaId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$manga = Manga::findFirst($mangaId);
		if (!$manga) {
			throw new Exception('Manga instance not found', 404);
		}

		$MCs = MangaChapter::find(array('conditions' => "manga_id = :id:", 'bind' => array('id' => $mangaId)));
		if (!$MCs) {
			throw new Exception('Query not executed', 500);
		}
		if ($MCs->count() == 0) {
			return array('code' => 204, 'content' => 'No matching MangaChapter instance found');
		}

		return array('code' => 200, 'content' => $MCs->toArray());
	}
}