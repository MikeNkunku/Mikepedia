<?php

namespace Models;

use BroadcastProgram;
use Status;

class TVSeries extends BroadcastProgram {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $broadcast_program_id;

	/**
	 * @var text
	 */
	protected $main_cast;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'tv_series';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('tv_series');
		$this->belongsTo('broadcast_program_id', 'Models\BroadcastProgram', 'id');
	}

	/**
	 * @param JSON $postData
	 * @return integer
	 */
	public function beforeCreate($postData) {
		$bp = new BroadcastProgram();
		$create = $bp->create(array(
				'type_id'=> $postData->typeId,
				'name' => $postData->name,
				'start_date' => date($postData->startDate, 'Y-m-d'),
				'end_date' => date($postData->endDate, 'Y-m-d'),
				'summary' => $postData->summary,
				'status_id'=> $postData->statusId,
				'created_at' => new \Datetime('now', new \DateTimeZone('UTC')),
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));

		return $create ? $bp->getId() : 0;
	}

	/**
	 * @param JSON $putData
	 * @return boolean
	 */
	public function beforeUpdate($putData) {
		$bp = BroadcastProgram::findFirst($this->getBroadcastProgramId());
		$update = $bp->update(array(
				'type_id'=> $putData->typeId,
				'name' => $putData->name,
				'start_date' => date($putData->startDate, 'Y-m-d'),
				'end_date' => date($putData->endDate, 'Y-m-d'),
				'summary' => $putData->summary,
				'status_id'=> $putData->statusId,
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));

		return $update ? true : false;
	}

	/**
	 * @return boolean
	 */
	public function delete() {
		$bp = BroadcastProgram::findFirst($this->getBroadcastProgramId());
		$s = Status::findFirst(array('name' => 'deleted'));
		$delete = $bp->update(array(
				'status_id' => $s->getId(),
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));
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
	public function getBroadcastProgramId() {
		return $this->broadcast_program_id;
	}

	/**
	 * @param integer $broadcastProgramId
	 */
	public function setBroadcastProgramId($broadcastProgramId) {
		$this->broadcast_program_id = $broadcastProgramId;
	}

	/**
	 * @return text
	 */
	public function getMainCast() {
		return $this->main_cast;
	}

	/**
	 * @param text $mainCast
	 */
	public function setMainCast($mainCast) {
		$this->main_cast = $mainCast;
	}
}