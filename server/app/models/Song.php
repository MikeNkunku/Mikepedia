<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Song extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $production_id;

	/**
	 * @var integer
	 */
	protected $number;

	/**
	 * @var integer
	 */
	protected $genre_id;

	/**
	 * @var text
	 */
	protected $title;

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
		return 'songs';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('songs');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->belongsTo('production_id', 'Models\Production', 'id');
		$this->belongsTo('genre_id', 'Models\SongGenre', 'id');
		$this->hasOne('id', 'Models\Lyrics', 'song_id');
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
	public function getProductionId() {
		return $this->production_id;
	}

	/**
	 * @param integer $productionId
	 */
	public function setProductionid($productionId) {
		$this->production_id = $productionId;
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