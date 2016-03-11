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
}