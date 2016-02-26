<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\Status;
use Models\Game;
use Models\GameGenre;
use Models\GamePlatform;
use Models\GameSection;

class GameController extends BaseController {
	/**
	 * @param integer $gameId
	 */
	public function get($gameId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$game = Game::findFirst($gameId);
		if (!$game) {
			throw new Exception('Game not found', 404);
		}

		$gameSections = GameSection::find(array(
				"game_id = :id:",
				'bind' => array('id' => $game->getId()),
				'order' => 'id ASC'
		));
		$gsArr = $gameSections->toArray('id', 'title', 'summary');

		$gArr = $game->toArray();
		$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
		$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
		$gArr['gameSections'] = $gsArr;

		return array('code' => 200, 'content' => $gArr);
	}

	public function add() {
		if (!$this->application->request->isPost()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$postData = $this->application->request->getJsonRawBody();
		if (empty($postData->title)) {
			throw new Exception('Title cannot be null', 409);
		}
		if (empty($postData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($postData->platforms)) {
			throw new Exception('Since when a game can be played without platform?', 409);
		}
		if (empty($postData->genres)) {
			throw new Exception('The game belongs to at least one genre', 409);
		}
		if (empty($postData->releaseYear)) {
			throw new Exception('Release year field must be filled', 409);
		}
		if (empty($postData->summary)) {
			throw new Exception('Summary field cannot be empty', 409);
		}
		$game = new Game();
		$game->beforeCreate();
		$create = $game->create(array(
				'title' => $postData->title,
				'summary' => $postData->summary,
				'genres' => $postData->genres,
				'platforms' => $postData->platforms,
				'release_year' => $postData->releaseYear,
				'status_id' => $postData->statusId
		));
		if (!$create) {
			throw new Exception('Game not created', 409);
		}

		$gArr = $game->toArray();
		$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
		$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);

		return array('code' => 201, 'content' => $gArr;
	}

	/**
	 * @param integer $gameId
	 */
	public function update($gameId) {
		if (!$this->application->request->isPut()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$game = Game::findFirst($gameId);
		if (!$game) {
			throw new Exception('Game not found', 404);
		}

		$putData = $this->application->request->getJsonRawBody();
		if (empty($putData->title)) {
			throw new Exception('Title cannot be null', 409);
		}
		if (empty($putData->statusId)) {
			throw new Exception('Status ID cannot be null', 409);
		}
		if (empty($putData->platforms)) {
			throw new Exception('Since when a game can be played without platform?', 409);
		}
		if (empty($putData->genres)) {
			throw new Exception('The game belongs to at least one genre', 409);
		}
		if (empty($putData->releaseYear)) {
			throw new Exception('Release year field must be filled', 409);
		}
		if (empty($putData->summary)) {
			throw new Exception('Summary field cannot be empty', 409);
		}
		$update = $game->update(array(
				'title' => $putData->title,
				'summary' => $putData->summary,
				'genres' => $putData->genres,
				'platforms' => $putData->platforms,
				'release_year' => $putData->releaseYear,
				'status_id' => $putData->statusId
		));
		if (!$update) {
			throw new Exception('Game not updated', 409);
		}

		$gArr = $game->toArray();
		$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
		$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);

		return array('code' => 200, 'content' => $gArr);
	}

	/**
	 * @param integer $gameId
	 */
	public function delete($gameId) {
		if (!$this->application->request->isDelete()) {
			throw new Exception('Method not allowed', 405);
		}

		if (!$this->isAllowed()) {
			throw new Exception('User not authorized', 401);
		}

		$game = Game::findFirst($gameId);
		if (!$gameId) {
			throw new Exception('Game not found', 404);
		}

		$statusD = Status::findFirst(array(
				'conditions' => "name = :status:",
				'bind' => array('status' => 'deleted')
		));
		if ($statusD->getId() == $game->getStatusId()) {
			throw new Exception('Game already deleted', 409);
		}

		$game->beforeUpdate();
		$delete = $game->update(array('status_id' => $statusD->getId()));
		if (!$delete) {
			throw new Exception('Game not deleted', 409);
		}

		return array('code' => 204, 'content' => 'Game deleted');
	}

	public function getAll() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$games = Games::find(array('order' => 'id ASC'));
		if ($games->count() == 0) {
			return array('code' => 200, 'content' => 'No games instanciated');
		}

		$output = array();
		foreach($games as $g) {
			$gArr = $g->toArray();
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	public function getValidList() {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statusD = Status::findFirst("name = 'deleted'"));
		$games = Game::query()
		->notInWhere('status_id', $statusD->getId())
		->order('release_year DESC')
		->execute();
		if (!$games) {
			throw new Exception('Query not executed', 409);
		}

		if ($games->count() == 0) {
			return array('code' => 200, 'content' => 'No game in database');
		}

		$output = array();
		foreach($games as $g) {
			$gArr = $g->toArray();
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param text $statusName
	 */
	public function getList($statusName) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$statuses = Status::find();
		$sArr = $statuses->toArray('name');
		if (!in_array($statusName, $sArr)) {
			throw new Exception('Invalid parameter', 409);
		}

		$status = Status::findFirst(array(
				'conditions' => "name = :name:",
				'bind' => array('name' => $statusName)
		));

		$games = Game::find(array(
				'conditions' => "status_id = :id:",
				'bind' => array('id' => $status->getId()),
				'order' => 'id ASC'
		));
		if (!$games) {
			throw new Exception('Query encountered error', 409);
		}

		if ($games->count() == 0) {
			return array('code' => 200, 'content' => 'No game in database');
		}

		$output = array();
		foreach($games as $g) {
			$gArr = $g->toArray();
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param integer $gameGenreId
	 */
	public function getByGenre($gameGenreId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$GGs = GameGenre::find();
		$ggArr = $GGs->toArray('id');
		if (!in_array($gameGenreId, $ggArr)) {
			throw new Exception('Invalid parameter', 409);
		}

		$games = Game::find();
		$ggArr = $GGS->toArray();
		$output = array();
		foreach($games as $g) {
			$genres = $g->getGenres();
			$temp = array();
			if (!in_array($gameGenreId, $genres)) {
				continue;
			}

			foreach($genres as $gg) {
				$genre = GameGenre::findFirst($gg);
				array_push($temp, $genre->getName());
			}

			$gArr = $g->toArray();
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			$gArr['genres'] = $temp;
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param integer $gamePlatformId
	 */
	public function getByPlatform($gamePlatformId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$GPs = GamePlatform::find();
		$gpIDs = $GPS->toArray('id');
		if (!in_array($gamePlatformId, $gpIDs)) {
			throw new Exception('Invalid parameter', 409);
		}

		$games = Games::find(array(
				'conditions' => "platforms LIKE ?1",
				'bind' => array(1 => '%'.$gamePlatformId.'%'),
				'order' => 'title ASC'
		));
		if (!$games) {
			throw new Exception('Query not executed', 409);
		}
		if ($games->count() == 0) {
			return array('code' => 200, 'content' => 'No matching game found in database');
		}

		$output = array();
		foreach($games as $g) {
			$platforms = $g->getPlaforms();
			$temp = array();
			foreach($platforms as $pID) {
				$gp = GamePlatform::findFirst($pID);
				array_push($temp, $gp->getName());
			}
			$gArr = $g->toArray();
			$gArr['platforms'] = $temp;
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}

	/**
	 * @param integer $year
	 */
	public function getByYear($year) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$games = Game::find(array(
				'conditions' => "release_year = :year:",
				'bind' => array('year' => $year),
				'order' => 'title ASC'
		));
		if (!$games) {
			throw new Exception('Query not executed', 409);
		}

		if ($games->count() == 0) {
			return array('code' => 200, 'content' => 'No matching game found in database');
		}

		$output = array();
		foreach($games as $g) {
			$gArr = $g->toArray();
			$gArr['created_at'] = date('Y-m-d H:i:sP', $gArr['created_at']);
			$gArr['updated_at'] = date('Y-m-d H:i:sP', $gArr['updated_at']);
			array_push($output, $gArr);
		}

		return array('code' => 200, 'content' => $output);
	}
}