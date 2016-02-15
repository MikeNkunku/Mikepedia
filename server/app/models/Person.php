<?php

namespace Models;

use Phalcon\Mvc\Model;

class Person extends Model {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $status_id;

	/**
	 * @var integer
	 */
	protected $type_id;

	/**
	 * @var text
	 */
	protected $firstname;

	/**
	 * @var text
	 */
	protected $lastname;

	/**
	 * @var character(1)
	 */
	protected $gender;

	/**
	 * @var text
	 */
	protected $nicknames;

	/**
	 * @var date
	 */
	protected $birthdate;

	/**
	 * @var text
	 */
	protected $picture;

	/**
	 * @var text
	 */
	protected $relationships;

	/**
	 * @var text
	 */
	protected $summary;

	/**
	 * @var text
	 */
	protected $biography;

	/**
	 * @var timestamp
	 */
	protected $created_at;

	/**
	 * @var timestamp
	 */
	protected $updated_at;

	/**
	 * Redefine mapping table.
	 */
	public function getSource() {
		return 'persons';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('persons');
		$this->belongsTo('status_id', 'Models\Status', 'id');
		$this->belongsTo('type_id', 'Models\PersonType', 'id');
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
	 * @return text
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param text $firstname
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @return text
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param text $lastname
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @return character(1)
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param character(1) $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return text
	 */
	public function getNicknames() {
		return $this->nicknames;
	}

	/**
	 * @param text $nicknames
	 */
	public function setNicknames($nicknames) {
		$this->nicknames = $nicknames;
	}

	/**
	 * @return date
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * @param date $birthdate
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;
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
	 * @return text
	 */
	public function getRelationships() {
		return $this->relationships;
	}

	/**
	 * @param text $relationships
	 */
	public function setRelationships($relationships) {
		$this->relationships = $relationships;
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
	public function getBiography() {
		return $this->biography;
	}

	/**
	 * @param text $biography
	 */
	public function setBiography($biography) {
		$this->biography = $biography;
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