<?php

namespace Models;

use Phalcon\Mvc\Model;

class GameGenre extends Model {
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
		return 'games_genres';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('games_genres');
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