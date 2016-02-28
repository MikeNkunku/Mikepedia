<?php 

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Manga extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * @var text
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $creator_id;

	/**
	 * @var integer
	 */
	protected $year;

	/**
	 * @var boolean
	 */
	protected $has_anime;

	/**
	 * @var text
	 */
	protected $genres;

	/**
	 * @var integer
	 */
	protected $demography_id;

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
		return 'mangas';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('mangas');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->belongsTo('creator_id', 'Models\Celebrity', 'id');
		$this->belongsTo('demography_id', 'Models\MangaPublic', 'id');
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
	 * @return integer
	 */
	public function getCreatorId() {
		return $this->creator_id;
	}

	/**
	 * @param integer $creatorId
	 */
	public function setCreatorId($creatorId) {
		$this->creator_id = $creatorId;
	}

	/**
	 * @return integer
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param integer $year
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * @return boolean
	 */
	public function getHasAnime() {
		return $this->has_anime;
	}

	/**
	 * @param boolean $hasAnime
	 */
	public function setHasAnime($hasAnime) {
		$this->has_anime = $hasAnime;
	}

	/**
	 * @return array[integer]
	 */
	public function getGenres() {
		return array_map('intval', explode($this->genres, '; '));
	}

	/**
	 * @param text $genres
	 */
	public function setGenres($genres) {
		$this->genres = $genres;
	}

	/**
	 * @return integer
	 */
	public function getDemographyId() {
		return $this->demography_id;
	}

	/**
	 * @param integer $demographyId
	 */
	public function setDemographyId($demographyId) {
		$this->demography_id = $demographyId;
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