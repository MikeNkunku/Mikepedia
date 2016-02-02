<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\GamePlatform;

class GamePlatformController extends BaseController {
	/**
	 * @param integer $gamePlatformId
	 */
	public function get($gamePlatformId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$gp = GamePlatform::findFirst($gamePlatformId);
		if (!$gp) {
			throw new Exception('GamePlatform not found', 404);
		}

		return array('code' => 200, 'content' => $gp->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$gp = GamePlatform::findFirst(array('name' => $postData->name));
		if ($gp) {
			throw new Exception('GamePlatform already created', 409);
		}

		$gp = new GamePlatform();
		$create = $gp->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('GameGenre not created', 409);
		}

		return array('code' => 201, 'content' => $gp->toArray());
	}

	/**
	 * @param integer $gamePlatformId
	 */
	public function delete($gamePlatformId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$gp = GamePlatform::findFirst($gamePlatformId);
		if (!$gp) {
			throw new Exception('GamePlatform not found', 404);
		}

		if (!$gp->delete()) {
			throw new Exception('GamePlatform not deleted', 409);
		}

		return array('code' => 204, 'content' => 'GamePlatform deleted');
	}
}