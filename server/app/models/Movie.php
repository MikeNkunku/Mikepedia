<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Movie extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $producer_id;

	/**
	 * @var timestamp
	 */
	protected $release_date;

	/**
	 * @var text
	 */
	protected $summary;

	/**
	 * @var text
	 */
	protected $description;

	/**
	 * @var text
	 */
	protected $genres;

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
	 * @return text
	 */
	public function getSource() {
		return 'movies';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('movies');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->belongsTo('producer_id', 'Models\Celebrity', 'id');
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
	public function getProducerId() {
		return $this->producer_id;
	}

	/**
	 * @param integer $producerId
	 */
	public function setProducerId($producerId) {
		$this->producer_id = $producerId;
	}

	/**
	 * @return timestamp
	 */
	public function getReleaseDate() {
		return $this->release_date;
	}

	/**
	 * @param timestamp $releaseDate
	 */
	public function setReleaseDate($releaseDate) {
		$this->release_date = $releaseDate;
	}

	/**
	 * @return text
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @param text $summary
	 */
	public function setSummary($summary) {
		$this->summary = $summary;
	}

	/**
	 * @return text
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param text $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return text
	 */
	public function getGenres() {
		return $this->genres;
	}

	/**
	 * @param text $genres
	 */
	public function setGenres($genres) {
		$this->genres = $genres;
	}

	/**
	 * @return timestamp
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @param timestamp $created_at
	 */
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}

	/**
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * @param timestamp $updated_at
	 */
	public function setUpdatedAt($updated_at) {
		$this->updated_at = $updated_at;
	}
}