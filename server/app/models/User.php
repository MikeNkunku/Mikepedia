<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class User extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var text
	 */
	protected $username;

	/**
	 * @var text
	 */
	protected $password;

	/**
	 * @var date
	 */
	protected $birthdate;

	/**
	 * @var integer
	 */
	protected $super_user;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * @var timestamp
	 */
	protected $created_at;

	/**
	 * @var timestamp
	 */
	protected $updated_at;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'users';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('users');
		$this->belongsTo('status_id', 'Models\Status', 'id');
	}

	public function beforeCreate() {
		$this->created_at = new \Datetime('now', new \DateTimeZone('UTC'));
		$this->updated_at = new \Datetime('now', new \DateTimeZone('UTC'));
	}

	public function beforeUpdate() {
		$this->updated_at = new \Datetime('now', new \DateTimeZone('UTC'));
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return text
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param text $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @return text
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param text $passwd
	 */
	public function setPassword($passwd) {
		$this->password = $passwd;
	}

	/**
	 * @return date
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * @param date $birthdate
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;
	}

	/**
	 * @return integer
	 */
	public function getSuperUser() {
		return $this->super_user;
	}

	/**
	 * @param integer $superUser
	 */
	public function setSuperUser($superUser) {
		$this->super_user = $superUser;
	}

	/**
	 * @return integer
	 */
	public function getStatusId() {
		return $this->status_id;
	}

	/**
	 * @param integer $statusId
	 */
	public function setStatusId($statusId) {
		$this->status_id = $statusId;
	}

	/**
	 * @return timestamp
	 */
	public function getCreatedAt() {
		return date('Y-m-d H:i:sP', $this->created_at);
	}

	/**
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return date('Y-m-d H:i:sP', $this->updated_at);
	}
}