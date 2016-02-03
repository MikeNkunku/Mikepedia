<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\MangaPublic;

class MangaPublicController extends BaseController {
	/**
	 * @param integer $mangaPublicId
	 */
	public function get($mangaPublicId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mp = MangaPublic::findFirst($mangaPublicId);
		if (!$mp) {
			throw new Exception('MangaPublic not found', 404);
		}

		return array('code' => 200, 'content' => $mp->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$mp = MangaPublic::findFirst(array('name' => $postData->name));
		if ($mp) {
			throw new Exception('MangaPublic already created', 409);
		}

		$mp = new MangaPublic();
		$create = $mp->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('MangaPublic not created', 409);
		}

		return array('code' => 201, 'content' => $mp->toArray());
	}

	/**
	 * @param integer $mangaPublicId
	 */
	public function delete($mangaPublicId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mp = MangaPublic::findFirst($mangaPublicId);
		if (!$mp) {
			throw new Exception('MangaPublic not found', 404);
		}

		if (!$mp->delete()) {
			throw new Exception('MangaPublic not deleted', 409);
		}

		return array('code' => 204, 'content' => 'MangaPublic deleted');
	}
}