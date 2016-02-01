<?php 

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Lyrics extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $song_id;

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
		return 'lyrics';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('lyrics');
		$this->belongsTo('song_id', 'Models\Song', 'id');
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
	public function getSongId() {
		return $this->song_id;
	}

	/**
	 * @param integer $songId
	 */
	public function setSongId($songId) {
		$this->song_id = $songId;
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