<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Status;
use Models\Production;

class ProductionController extends BaseController {
	/**
	* @param integer $productionId
	*/
	public function get($productionId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$production = Production::findFirst($productionId);
		if (!$production) {
			throw new Exception('Production instance not found', 404);
		}

		return array('code' => 200, 'content' => $production->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->typeId)) {
			throw new Exception('Type ID field cannot be null', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}
		if (empty($postData->name)) {
			throw new Exception('Name field cannot be empty', 409);
		}
		if (empty($postData->artistId)) {
			throw new Exception('Artist ID field must be filled', 409);
		}
		if (empty($postData->releaseDate)) {
			throw new Exception('Release date attribute cannot be null', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}

		$production = new Production();
		$production->beforeCreate();
		$create = $production->create(array(
			'type_id' => $postData->typeId,
			'status_id' => $postData->statusId,
			'name' => $postData->name,
			'artist_id' => $postData->artistId,
			'release_date' => $postData->releaseDate,
			'summary' => $postData->summary
		));
		if (!$create) {
			throw new Exception('Production instance not created', 500);
		}

		return array('code' => 201, 'content' => $production->toArray());
	}

	/**
	* @param integer $productionId
	*/
	public function update($productionId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$production = Production::findFirst($productionId);
		if (!$production) {
			throw new Exception('Production instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->typeId)) {
			throw new Exception('Type ID field cannot be null', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}
		if (empty($putData->name)) {
			throw new Exception('Name field cannot be empty', 409);
		}
		if (empty($putData->artistId)) {
			throw new Exception('Artist ID field must be filled', 409);
		}
		if (empty($putData->releaseDate)) {
			throw new Exception('Release date attribute cannot be null', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}

		$production->beforeUpdate();
		$update = $production->update(array(
			'type_id' => $putData->typeId,
			'status_id' => $putData->statusId,
			'name' => $putData->name,
			'artist_id' => $putData->artistId,
			'release_date' => $putData->releaseDate,
			'summary' => $putData->summary
		));
		if (!$update) {
			throw new Exception('Production instance not updated', 500);
		}

		return array('code' => 200, 'content' => $production->toArray());
	}
}