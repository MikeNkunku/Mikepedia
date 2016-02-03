<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\SongGenre;

class SongGenreController extends BaseController {
	/**
	 * @param integer $songGenreId
	 */
	public function get($songGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$sg = SongGenre::findFirst($songGenreId);
		if (!$sg) {
			throw new Exception('SongGenre not found', 404);
		}

		return array('code' => 200, 'content' => $sg->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$sg = SongGenre::findFirst(array('name' => $postData->name));
		if ($sg) {
			throw new Exception('SongGenre already created', 409);
		}

		$sg = new SongGenre();
		$create = $sg->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('SongGenre not created', 409);
		}

		return array('code' => 201, 'content' => $sg->toArray());
	}

	/**
	 * @param integer $songGenreId
	 */
	public function delete($songGenreId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$sg = SongGenre::findFirst($songGenreId);
		if (!$sg) {
			throw new Exception('SongGenre not found', 404);
		}

		if (!$sg->delete()) {
			throw new Exception('SongGenre not deleted', 409);
		}

		return array('code' => 204, 'content' => 'SongGenre deleted');
	}
}