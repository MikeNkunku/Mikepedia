<?php

namespace Models;

use Phalcon\Mvc\Model;

class BroadcastProgram extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $type_id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * @var timestamp
	 */
	protected $start_date;

	/**
	 * @var timestamp
	 */
	protected $end_date;

	/**
	 * @var text
	 */
	protected $summary;

	/**
	 * @var integer
	 */
	protected $status_id;

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
		return 'broadcasts_programs';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('broadcasts_programs');
		$this->belongsTo('type_id', 'Models\BroadcastType', 'id');
		$this->belongsTo('status_id', 'Models\Status', 'id');
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
	 * @return timestamp
	 */
	public function getStartDate() {
		return $this->start_date;
	}

	/**
	 * @param timestamp $startDate
	 */
	public function setStartDate($startDate) {
		$this->start_date = $startDate;
	}

	/**
	 * @return timestamp
	 */
	public function getEndDate() {
		return $this->end_date;
	}

	/**
	 * @param timestamp $endDate
	 */
	public function setEndDate($endDate) {
		$this->end_date = $endDate;
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
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}
}