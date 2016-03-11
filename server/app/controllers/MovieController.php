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
}