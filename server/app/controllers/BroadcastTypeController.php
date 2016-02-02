<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\BroadcastType;

class BroadcastTypeController extends BaseController {
	/**
	 * @param integer $broadcastTypeId
	 */
	public function get($broadcastTypeId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$bt = BroadcastType::findFirst($broadcastTypeId);
		if (!$bt) {
			throw new Exception('BroadcastType not found', 404);
		}

		return array('code' => 200, 'content' => $bt->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$bt = BroadcastType::findFirst(array('name' => $postData->name));
		if ($bt) {
			throw new Exception('BroadcastType already created', 409);
		}

		$bt = new BroadcastType();
		$create = $bt->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('BroadcastType not created', 409);
		}

		return array('code' => 201, 'content' => $bt->toArray());
	}

	/**
	 * @param integer $broadcastTypeId
	 */
	public function delete($broadcastTypeId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$bt = BroadcastType::findFirst($broadcastTypeId);
		if (!$bt) {
			throw new Exception('BroadcastType not found', 404);
		}

		if (!$bt->delete()) {
			throw new Exception('BroadcastType not deleted', 409);
		}

		return array('code' => 204, 'content' => 'BroadcastType deleted');
	}
}