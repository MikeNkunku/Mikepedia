<?php

namespace Models;

use Phalcon\Mvc\Model;

class FictionalCharacterType extends Model {
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
		return 'fictional_characters_types';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('fictional_characters_types');
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