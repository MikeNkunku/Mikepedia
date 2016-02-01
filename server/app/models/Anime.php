<?php 

namespace Models;

use BroadcastProgram;
use Status;

class Anime extends BroadcastProgram {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $broadcast_program_id;

	/**
	 * @var integer
	 */
	protected $manga_id;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'animes';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('animes');
		$this->belongsTo('broadcast_program_id', 'Models\BroadcastProgram', 'id');
		$this->belongsTo('manga_id', 'Models\Manga', 'id');
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

		return $create ? $bp->getId() : false;
	}

	/**
	 * @param JSON $putData
	 * @return integer
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

		return $update ? $bp->getId() : false;
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
	 * @return integer
	 */
	public function getMangaId() {
		return $this->manga_id;
	}

	/**
	 * @param integer $mangaId
	 */
	public function setMangaId($mangaId) {
		$this->manga_id = $mangaId;
	}
}