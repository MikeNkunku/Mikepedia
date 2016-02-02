<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\GameGenre;

class GameGenreController extends BaseController {
	/**
	 * @param integer $gameGenreId
	 */
	public function get($gameGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$gg = GameGenre::findFirst($gameGenreId);
		if (!$gg) {
			throw new Exception('GameGenre not found', 404);
		}

		return array('code' => 200, 'content' => $gg->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$gg = GameGenre::findFirst(array('name' => $postData->name));
		if ($gg) {
			throw new Exception('GameGenre already created', 409);
		}

		$gg = new GameGenre();
		$create = $gg->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('GameGenre not created', 409);
		}

		return array('code' => 201, 'content' => $gg->toArray());
	}

	/**
	 * @param integer $gameGenreId
	 */
	public function delete($gameGenreId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$gg = GameGenre::findFirst($gameGenreId);
		if (!$gg) {
			throw new Exception('GameGenre not found', 404);
		}

		if (!$gg->delete()) {
			throw new Exception('GameGenre not deleted', 409);
		}

		return array('code' => 204, 'content' => 'GameGenre deleted');
	}
}