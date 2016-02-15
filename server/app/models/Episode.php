<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Episode extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $season_id;

	/**
	 * @var integer
	 */
	protected $number;

	/**
	 * @var text
	 */
	protected $summary;

	/**
	 * @var text
	 */
	protected $description;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * @var timestamp
	 */
	protected $aired_at;

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
		return 'episodes';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('episodes');
		$this->belongsTo('season_id', 'Models\Season', 'id');
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
	 * @return integer
	 */
	public function getSeasonId() {
		return $this->season_id;
	}

	/**
	 * @param integer $seasonId
	 */
	public function setSeasonId($seasonId) {
		$this->season_id = $seasonId;
	}

	/**
	 * @return integer
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param integer $number
	 */
	public function setNumber($number) {
		$this->number = $number;
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
	 * @var timestamp
	 */
	public function getAiredAt() {
		return $this->aired_at;
	}

	/**
	 * @param timestamp $airedAt
	 */
	public function setAiredAt($airedAt) {
		$this->aired_at = $airedAt;
	}

	/**
	 * @return timestamp
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}
}