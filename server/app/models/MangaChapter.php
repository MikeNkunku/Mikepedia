<?php 

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class MangaChapter extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $number;

	/**
	 * @var integer
	 */
	protected $manga_id;

	/**
	 * @var text
	 */
	protected $summary;

	/**
	 * @var text
	 */
	protected $content;

	/**
	 * @var text
	 */
	protected $picture;

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
	 * @return text
	 */
	public function getSource() {
		return 'mangas_chapters';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('mangas_chapters');
		$this->belongsTo('manga_id', 'Models\Manga', 'id');
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
	 * @return text
	 */
	public function getPicture() {
		return $this->picture;
	}

	/**
	 * @param text $picture
	 */
	public function setPicture($picture) {
		$this->picture = $picture;
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