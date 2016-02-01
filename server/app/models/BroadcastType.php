<?php

namespace Models;

use Phalcon\Mvc\Model;

class BroadcastType extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $name;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'broadcasts_types';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('broadcasts_types');
	}

	/**
	 * @param JSON $postData
	 * @return boolean
	 */
	public function beforeCreate($postData) {
		$bt = BroadcastType::findFirst(array('name' => $postData->name));

		return $bt ? false : true;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @var text
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