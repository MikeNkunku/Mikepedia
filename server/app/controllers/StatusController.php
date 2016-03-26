<?php

namespace Controllers;

use Phalcon\Exception;
use BaseController;
use Models\Status;

class StatusController extends BaseController {
	/**
	 * @param integer $statusId
	 */
	public function get($statusId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$status = Status::findFirst($statusId);
		if (!$status) {
			throw new Exception('Status instance not found', 404);
		}

		return array('code' => 200, 'content' => $status->toArray());
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();

		$status = Status::findFirst(array('name' => $postData->name));
		if ($status) {
			throw new Exception('Status already created', 409);
		}

		$status = new Status();
		$create = $status->create(array('name' => $postData->name));

		if (!$create) {
			throw new Exception('Status not created', 500);
		}

		return array('code' => 201, 'content' => $status->toArray());
	}

	/**
	 * @param integer $statusId
	 */
	public function delete($statusId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}
		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$status = Status::findFirst($statusId);
		if (!$status) {
			throw new Exception('Status not found', 404);
		}

		if (!$status->delete()) {
			throw new Exception('Status not deleted', 500);
		}

		return array('code' => 204, 'content' => 'Status instance deleted');
	}
}