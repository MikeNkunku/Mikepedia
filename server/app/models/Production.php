<?php

namespace Models;

use Phalcon\Mvc\Model;

class Production extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $type_id;

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
	protected $artist_id;

	/**
	 * @var timestamp
	 */
	protected $release_date;

	/**
	 * @var text
	 */
	protected $summary;

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
		return 'productions';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('productions');
		$this->belongsTo('type_id', 'Models\ProductionType', 'id');
		$this->belongsTo('artist_id', 'Models\Celebrity', 'id');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->hasMany('id', 'Models\Song', 'production_id');
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
	public function getTypeId() {
		return $this->type_id;
	}

	/**
	 * @param integer $typeId
	 */
	public function setTypeId($typeId) {
		$this->type_id = $typeId;
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
	public function getArtistId() {
		return $this->artist_id;
	}

	/**
	 * @param integer $artistId
	 */
	public function setArtistId($artistId) {
		$this->artist_id = $artistId;
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
		return $this->created_at;
	}

	/**
	 * @param timestamp $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->created_at = $createdAt;
	}

	/**
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * @param timestamp $updatedAt
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updated_at = $updatedAt;
	}
}