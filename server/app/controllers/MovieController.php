<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Status;
use Models\Movie;

class MovieController extends BaseController {
	/**
	* @param integer $movieId
	*/
	public function get($movieId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$movie = Movie::findFirst($movieId);
		if (!$movie) {
			throw new Exception('Movie instance not found', 404);
		}

		return array('code' => 200, 'content' => $movie->toArray());
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
			throw new Exception('Status ID field cannot be null', 409);
		}
		if (empty($postData->name)) {
			throw new Exception('A movie must have a name', 409);
		}
		if (empty($postData->producerId)) {
			throw new Exception('Producer ID cannot be null', 409);
		}
		if (empty($postData->releaseDate)) {
			throw new Exception('Release date field must be filled', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}
		if (empty($postData->description)) {
			throw new Exception('Description field cannot be empty', 409);
		}
		if (empty($postData->genres)) {
			throw new Exception('A movie must at lease belong to one genre', 409);
		}

		$movie = new Movie();
		$movie->beforeCreate();
		$create = $movie->create(array(
			'status_id'	=> $postData->statusId,
			'name' => $postData->name,
			'producer_id' => $postData->producerId,
			'release_date' => $postData->releaseDate,
			'summary' => $postData->summary,
			'description' => $postData->description,
			'genres' => $postData->genres
		));
		if (!$create) {
			throw new Exception('Movie instance not created', 409);
		}

		return array('code' => 201, 'content' => $movie->toArray());
	}

	/**
	* @param integer $movieId
	*/
	public function update() {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$movie = Movie::findFirst($movieId);
		if (!$movie) {
			throw new Exception('Movie instance not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->statusId)) {
			throw new Exception('Status ID field cannot be null', 409);
		}
		if (empty($putData->name)) {
			throw new Exception('A movie must have a name', 409);
		}
		if (empty($putData->producerId)) {
			throw new Exception('Producer ID cannot be null', 409);
		}
		if (empty($putData->releaseDate)) {
			throw new Exception('Release date field must be filled', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field must be filled', 409);
		}
		if (empty($putData->description)) {
			throw new Exception('Description field cannot be empty', 409);
		}
		if (empty($putData->genres)) {
			throw new Exception('A movie must at lease belong to one genre', 409);
		}

		$movie->beforeUpdate();
		$update = $movie->create(array(
			'status_id'	=> $putData->statusId,
			'name' => $putData->name,
			'producer_id' => $putData->producerId,
			'release_date' => $putData->releaseDate,
			'summary' => $putData->summary,
			'description' => $putData->description,
			'genres' => $putData->genres
		));
		if (!$update) {
			throw new Exception('Movie instance not updated', 409);
		}

		return array('code' => 200, 'content' => $movie->toArray());
	}

	/**
	* @param integer $movieId
	*/
	public function delete($movieId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$movie = Movie::findFirst($movieId);
		if (!$movie) {
			throw new Exception('Movie instance not found', 404);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		if ($movie->getStatusId() == $statusD->getId()) {
			throw new Exception('Movie instance already deleted', 409);
		}

		$movie->beforeUpdate();
		$delete = $movie->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Movie instance not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Movie instance deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$movies =  Movie::find(array('order' => 'created_at ASC'));
		if (!$movies) {
			throw new Exception('Query not executed', 409);
		}
		if ($movies->count() == 0) {
			return array('code' => 204, 'content' => 'No Movie instance present in database');
		}

		return array('code' => 200, 'content' => $movies->toArray());
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'");
		$movies = Movie::query()
		->notInWhere('status_id', $statusD->getId())
		->order('updated_at DESC')
		->execute();
		if (!$movies) {
			throw new Exception('Query not executed', 409);
		}
		if ($movies->count() == 0) {
			return array('code' => 204, 'content' => 'No matching Movie instance found');
		}

		return array('code' => 200, 'content' => $movies->toArray());
	}
}