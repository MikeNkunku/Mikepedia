<?php

namespace Models;

use Phalcon\Mvc\Model;

class PersonType extends Model {
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
		return 'persons_types';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('persons_types');
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