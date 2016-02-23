<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\MangaGenre;

class MangaGenreController extends BaseController {
	/**
	 * @param integer $mangaGenreId
	 */
	public function get($mangaGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$mg = MangaGenre::findFirst($mangaGenreId);
		if (!$mg) {
			throw new Exception('MangaGenre not found', 404);
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

		$mg = MangaGenre::findFirst(array(
			'conditions' => "name = :name:",
			'bind' => array('name' => $postData->name)
		));
		if ($mg) {
			throw new Exception('MangaGenre already created', 409);
		}

		$mg = new MangaGenre();
		$create = $mg->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('MangaGenre not created', 409);
		}

		return array('code' => 201, 'content' => $mg->toArray());
	}

	/**
	 * @param integer $mangaGenreId
	 */
	public function delete($mangaGenreId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$mg = MangaGenre::findFirst($mangaGenreId);
		if (!$mg) {
			throw new Exception('MangaGenre not found', 404);
		}

		if (!$mg->delete()) {
			throw new Exception('MangaGenre not deleted', 409);
		}

		return array('code' => 204, 'content' => 'MangaGenre deleted');
	}
}