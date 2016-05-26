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
			throw new Exception('Parent class not created', 500);
		}

		$create = $mc->create(array(
			'person_id' => $pId,
			'manga_id' => $postData->mangaId,
			'personality' => $postData->personality
		));
		if (!$create) {
			throw new Exception('MangaCharacter instance not created', 500);
		}

		$p = Person::findFirst($pId);
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 201, 'content' => array_merge($mc->toArray(), $pArr));
	}

	/**
	* @param integer $mangaCharacterId
	*/
	public function update($mangaCharacterId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mc = MangaCharacter::findFirst($mangaCharacterId);
		if (!$mc) {
			throw new Exception('MangaCharacter instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field must not be empty', 409);
		}
		if (empty($putData->firstname)) {
			throw new Exception('Firstname field cannot be empty', 409);
		}
		if (empty($putData->lastname)) {
			throw new Exception('Lastname field cannot be empty', 409);
		}
		if (empty($putData->gender)) {
			throw new Exception('Gender field must not be empty', 409);
		}
		if (empty($putData->birthdate)) {
			throw new Exception('Birthdate field must not be empty', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must not be empty', 409);
		}
		if (empty($putData->biography)) {
			throw new Exception('Biography field cannot be empty', 409);
		}
		if (empty($putData->picture)) {
			throw new Exception('Picture field must be filled', 409);
		}
		if (empty($putData->mangaId)) {
			throw new Exception('Manga ID field cannot be null', 409);
		}
		if (empty($putData->personality)) {
			throw new Exception('Personality attribute must be filled', 409);
		}

		$preUpdate = $mc->beforeUpdate($putData);
		if (!$preUpdate) {
			throw new Exception('Parent class not updated', 500);
		}

		$update = $mc->update(array(
			'manga_id' => $putData->mangaId,
			'personality' => $putData->personality,
			'person_id' => $putData->personId
		));
		if (!$update) {
			throw new Exception('MangaCharacter instance not updated', 500);
		}

		$p = Person::findFirst($mc->getPersonId());
		$pArr = $p->toArray();
		unset($pArr['id']);

		return array('code' => 200, 'content' => array_merge($mc->toArray(), $pArr));
	}

	/**
	* @param integer $mangaCharacterId
	*/
	public function delete($mangaCharacterId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mc = MangaCharacter::findFirst($mangaCharacterId);
		if (!$mc) {
			throw new Exception('MangaCharacter instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$p = Person::findFirst($mc->getPersonId());
		if ($p->getStatusId() == $statusD->getId()) {
			throw new Exception('MangaCharacter instance already deleted', 409);
		}

		$delete = $p->update(array(
			'status_id' => $statusD->getId(),
			'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
		if (!$delete) {
			throw new Exception('MangaCharacter instance not deleted', 500);
		}

		return array('code' => 204, 'content' => 'MangaCharacter instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$MCs = MangaCharacter::find(array(
			'order' => 'id ASC',
			'group' => 'manga_id'
		));
		if (!$MCs) {
			throw new Exception('Query not executed', 500);
		}
		if ($MCs->count() == 0) {
			return array('code' => 204, 'content' => 'No MangaCharacter instance in database');
		}

		$output = array();
		foreach ($MCs as $mc) {
			$p = Person::findFirst($mc->getPersonId());
			$pArr = $p->toArray();
			unset($pArr['id']);
			array_push($output, array_merge($mc->toArray(), $pArr));
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$MCs = Person::query()
		->notInWhere('status_id', $statusD->getId())
		->rightJoin('Person', 'p.id = MangaCharacter.person_id', 'p')
		->orderBy('id ASC')
		->groupBy('manga_id')
		->execute();
		if (!$MCs) {
			throw new Exception('Query not executed', 500);
		}
		if ($MCs->count() == 0) {
			return array('code' => 204, 'content' => 'No matching MangaCharacter instance found');
		}

		$output = array();
		foreach ($MCs as $mc) {
			$p = Person::findFirst($mc->getPersonId());
			$pArr = $p->toArray();
			unset($pArr['id']);
			array_push($output, array_merge($mc->toArray(), $pArr));
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
		$statNames = $statuses->toArray('name');
		if (!in_array($statuses, $statNames)) {
			throw new Exception('Invalid parameter', 409);
		}

		$status = Status::findFirst(array('conditions' => "name = :name:", 'bind' => array('name' => $statusName)));
		$MCs = MangaCharacter::find(array(
			'conditions' => "status_id = :id:",
			'bind' => array('id' => $status->getId())
		));
		if (!$MCs) {
			throw new Exception('Query not executed', 500);
		}
		if ($MCs->count() == 0) {
			return array('code' => 204, 'content' => 'No matching MangaCharacter instance found');
		}

		$output = array();
		foreach ($MCs as $mc) {
			$p = Person::findFirst($mc->getPersonId());
			$pArr = $p->toArray();
			unset($pArr['id']);
			array_push($output, array_merge($mc->toArray(), $pArr));
		}

		return array('code' => 200, 'content' => $output);	
	}
}