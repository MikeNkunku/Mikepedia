<?php

namespace Models;

use Person;
use Status;

class Celebrity extends Person {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $person_id;

	/**
	 * @var text
	 */
	protected $early_life;

	/**
	 * Define mapping table.
	 * @return text
	 */
	public function getSource() {
		return 'celebrities';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('celebrities');
		$this->belongsTo('person_id', 'Models\Person', 'id');
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
				'status' => $s->getId(),
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
	 * @return text
	 */
	public function getEarlyLife() {
		return $this->early_life;
	}

	/**
	 * @param text $earlyLife
	 */
	public function setEarlyLife($earlyLife) {
		$this->early_life = $earlyLife;
	}
}