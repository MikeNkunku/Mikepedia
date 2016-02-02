<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\MovieGenre;

class MovieGenreController extends BaseController {
	/**
	 * @param integer $movieGenreId
	 */
	public function get($movieGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mg = MovieGenre::findFirst($movieGenreId);
		if (!$mg) {
			throw new Exception('MovieGenre not found', 404);
		}

		return array('code' => 200, 'content' => $mg->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$mg = MovieGenre::findFirst(array('name' => $postData->name));
		if ($mg) {
			throw new Exception('MovieGenre already created', 409);
		}

		$mg = new MovieGenre();
		$create = $mg->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('MovieGenre not created', 409);
		}

		return array('code' => 201, 'content' => $mg->toArray());
	}

	/**
	 * @param integer $movieGenreId
	 */
	public function delete($movieGenreId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mg = MovieGenre::findFirst($movieGenreId);
		if (!$mg) {
			throw new Exception('MovieGenre not found', 404);
		}

		if (!$mg->delete()) {
			throw new Exception('MovieGenre not deleted', 409);
		}

		return array('code' => 204, 'content' => 'MovieGenre deleted');
	}
}