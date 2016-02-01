<?php

namespace Models;

use Phalcon\Mvc\Model;

class ProductionType extends Model {
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
		return 'productions_types';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('productions_types');
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