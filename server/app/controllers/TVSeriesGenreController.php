<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\TVSeriesGenre;

class TVSeriesGenreController extends BaseController {
	/**
	 * @param integer $tvSeriesGenreId
	 */
	public function get($tvSeriesGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$tvsg = TVSeriesGenre::findFirst($tvSeriesGenreId);
		if (!$tvsg) {
			throw new Exception('TVSeriesGenre not found', 404);
		}

		return array('code' => 200, 'content' => $tvsg->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$tvsg = TVSeriesGenre::findFirst(array('name' => $postData->name));
		if ($tvsg) {
			throw new Exception('TVSeriesGenre already created', 409);
		}

		$tvsg = new TVSeriesGenre();
		$create = $tvsg->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('TVSeriesGenre not created', 409);
		}

		return array('code' => 201, 'content' => $tvsg->toArray());
	}

	/**
	 * @param integer $tvSeriesGenreId
	 */
	public function delete($TVSeriesGenreId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$tvsg = TVSeriesGenre::findFirst($tvSeriesGenreId);
		if (!$tvsg) {
			throw new Exception('TVSeriesGenre not found', 404);
		}

		if (!$tvsg->delete()) {
			throw new Exception('TVSeriesGenre not deleted', 409);
		}

		return array('code' => 204, 'content' => 'TVSeriesGenre deleted');
	}
}