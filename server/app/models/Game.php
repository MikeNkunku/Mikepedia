<?php

namespace Models;

use Phalcon\Mvc\Model;
use Status;

class Game extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var text
	 */
	protected $title;

	/**
	 * @var text
	 */
	protected $genres;

	/**
	 * @var text
	 */
	protected $platforms;

	/**
	 * @var integer
	 */
	protected $release_year;

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
		return 'games';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('games');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->hasMany('id', 'Models\GameSection', 'game_id');
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
	 * @return integer[]
	 */
	public function getGenres() {
		return array_map('intval', explode('; ', $this->genres));
	}

	/**
	 * @param text $genres
	 */
	public function setGenres($genres) {
		$this->genres = $genres;
	}

	/**
	 * @return integer[]
	 */
	public function getPlatforms() {
		return array_map('intval', explode('; ', $this->platforms));
	}

	/**
	 * @param text $platforms
	 */
	public function setPlatforms($platforms) {
		$this->platforms = $platforms;
	}

	/**
	 * @return integer
	 */
	public function getReleaseYear() {
		return $this->release_year;
	}

	/**
	 * @param integer $releaseYear
	 */
	public function setReleaseYear($releaseYear) {
		$this->release_year = $releaseYear;
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