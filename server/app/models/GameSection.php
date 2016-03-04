<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class GameSection extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $game_id;

	/**
	 * @var text
	 */
	protected $title;

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
		return 'games_sections';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('games_sections');
		$this->belongsTo('game_id', 'Models\Game', 'id');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->hasMany('id', 'Models\GameSubsection', 'gamesection_id');
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
	public function getGameId() {
		return $this->game_id;
	}

	/**
	 * @param integer $gameId
	 */
	public function setGameId() {
		$this->game_id = $gameId;
	}

	/**
	 * @return text
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param text $title
	 */
	public function setTitle($title) {
		$this->title = $title;
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
		return date('Y-m-d H:i:sP', $this->created_at);
	}

	/**
	 * @return timestamp
	 */
	public function getUpdatedAt() {
		return date('Y-m-d H:i:sP', $this->updated_at);
	}
}