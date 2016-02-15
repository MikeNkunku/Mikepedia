<?php

namespace Models;

use Person;

class FictionalCharacter extends Person {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $actor_id;

	/**
	 * @var text
	 */
	protected $personality;

	/**
	 * @var integer
	 */
	protected $person_id;

	/**
	 * @var integer
	 */
	protected $type_id;

	/**
	 * @var integer
	 */
	protected $media_id;

	/**
	 * Define mapping table
	 * @return text
	 */
	public function getSource() {
		return 'fictional_characters';
	}

	/**
	 * Define relationships with other models
	 */
	public function initialize() {
		$this->setSource('fictional_characters');
		$this->belongsTo('actor_id', 'Models\Celebrity', 'id');
		$this->belongsTo('person_id', 'Models\Person', 'id');
		$this->belongsTo('type_id', 'Models\FictionalCharacterType', 'id');
	}

	/**
	 * @param JSON $postData
	 * @return integer
	 */
	public function beforeCreate($postData) {
		$p = new Person();
		$create = $p->create(array(
				'firstname' => $postData->firstname,
				'status_id' => $postData->statusId,
				'lastname' => $postData->lastname,
				'gender' => $postData->gender,
				'nicknames' => $postData->nicknames,
				'birthdate' => date($postData->birthdate, 'Y-m-d'),
				'picture' => $postData->picture,
				'relationships' => $postData->relationships,
				'summary' => $postData->summary,
				'biography' => $postData->biography,
				'created_at' => new \Datetime('now', new \DateTimeZone('UTC')),
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));

		return $create ? $p->getId() : false;
	}

	/**
	 * @param JSON $putData
	 * @return integer
	 */
	public function beforeUpdate($putData) {
		$p = Person::findFirst($this->getPersonId());
		$update = $p->update(array(
				'firstname' => $putData->firstname,
				'status_id' => $putData->statusId,
				'lastname' => $putData->lastname,
				'gender' => $putData->gender,
				'nicknames' => $putData->nicknames,
				'birthdate' => date($putData->birthdate, 'Y-m-d'),
				'picture' => $putData->picture,
				'relationships' => $putData->relationships,
				'summary' => $putData->summary,
				'biography' => $putData->biography,
				'updated_at' => new \Datetime('now', new \DateTimeZone('UTC'))
		));

		return $update ? true : false;
	}

	public function delete() {
		$p = Person::findFirst($this->getPersonId());
		$s = Status::findFirst(array('name' => 'deleted'));
		$delete = $p->update(array(
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
	public function getActorId() {
		return $this->actor_id;
	}

	/**
	 * @param integer $actorId
	 */
	public function setActorId($actorId) {
		$this->actor_id = $actorId;
	}

	/**
	 * @return text
	 */
	public function getPersonality() {
		return $this->personality;
	}

	/**
	 * @param text $personality
	 */
	public function setPersonality($personality) {
		$this->personality = $personality;
	}

	/**
	 * @return integer
	 */
	public function getPersonId() {
		return $this->person_id;
	}

	/**
	 * @param integer $personId
	 */
	public function setPersonId($personId) {
		$this->person_id = $personId;
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
	 * @return integer
	 */
	public function getMediaId() {
		return $this->media_id;
	}

	/**
	 * @param integer $mediaId
	 */
	public function setMediaId($mediaId) {
		$this->media_id = $mediaId;
	}
}