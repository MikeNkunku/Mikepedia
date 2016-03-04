<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class GameSubsection extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $gamesection_id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * @var text
	 */
	protected $content;

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
		return 'games_subsections';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('games_subsections');
		$this->belongsTo('gamesection_id', 'Models\GameSection', 'id');
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
	public function getGameSectionId() {
		return $this->gamesection_id;
	}

	/**
	 * @param integer $gameSectionId
	 */
	public function setGameSectionId() {
		$this->gamesection_id = $gameSectionId;
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
	 * @return text
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param text $content
	 */
	public function setContent($content) {
		$this->content = $content;
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