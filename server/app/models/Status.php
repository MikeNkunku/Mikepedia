<?php

namespace Models;

use Phalcon\Mvc\Model;

class Status extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'status';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('status');
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
	public function getName() {
		return $this->name;
	}

	/**
	 * @param text $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
}