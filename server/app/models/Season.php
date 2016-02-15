<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Season extends Model {
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
	protected $program_id;

	/**
	 * @var integer
	 */
	protected $number;

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
		return 'seasons';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('seasons');
		$this->belongsTo('type_id', 'Models\BroadcastType', 'id');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->belongsTo('program_id', 'Models\BroadcastProgram', 'id');
		$this->hasMany('id', 'Models\Episode', 'season_id');
	}

	public function beforeCreate() {
		$this->created_at = new \Datetime('now', new \DateTimeZone('UTC'));
		$this->updated_at = new \Datetime('now', new \DateTimeZone('UTC'));
	}

	public function beforeUpdate() {
		$this->updated_at = new \Datetime('now', new \DateTimeZone('UTC'));
	}

	/**
	 * @return boolean
	 */
	public function delete() {
		$s = Status::findFirst(array('name' => 'deleted'));
		$delete = $this->update(array(
				'status_id' => $s->getId(),
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));

		return $delete ? true : false;
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
	 * @return integer
	 */
	public function getProgramId() {
		return $this->program_id;
	}

	/**
	 * @param integer $programId
	 */
	public function setProgramId($programId) {
		$this->program_id = $programId;
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