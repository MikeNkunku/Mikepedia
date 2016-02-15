<?php 

namespace Models;

use Person;
use Status;

class MangaCharacter extends Person {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	protected $person_id;

	/**
	 * @var integer
	 */
	protected $manga_id;

	/**
	 * @var text
	 */
	protected $personality;

	/**
	 * Define mapping table.
	 */
	public function getSource() {
		return 'manga_characters';
	}

	/**
	 * Define relationships with other models.
	 */
	public function initialize() {
		$this->setSource('manga_characters');
		$this->belongsTo('person_id', 'Models\Person', 'id');
		$this->belongsTo('manga_id', 'Models\Manga', 'id');
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
				'type_id' => 'MangaCharacter',
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
	public function getPersonality() {
		return $this->personality;
	}

	/**
	 * @param text $personality
	 */
	public function setPersonality($personality) {
		$this->personality = $personality;
	}
}