<?php

namespace Models;

use Phalcon\Mvc\Model;

class MangaGenre extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * Define mapping table.
	 * @return text
	 */
	public function getSource() {
		return 'mangas_genres';
	}

	/**
	 * Define relationships with other tables.
	 */
	public function initialize() {
		$this->setSource('mangas_genres');
		$this->belongsTo('status_id', 'Models\Status', 'id');
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
}