<?php

namespace Controllers;

use Phalcon\Mvc\Controller;
use Models\User;

class BaseController extends Controller {
	/**
	 * Basic function to check if user is allowed to execute POST, PUT & DELETE routes.
	 * @return boolean
	 */
	public function isAllowed() {
		if (!$this->session->has('auth')) {
			return false;
		}

		$user = User::findFirst(array('username' => $this->session->get('auth')));

		return $user->getSuperUser() ? true : false;
	}
}