<?php

namespace Controllers;

use Phalcon\Exception;

use BaseController;
use Models\BroadcastProgram;
use Models\TVSeries;
use Models\Status;

class TVSeriesController extends BaseController {
	/**
	 * @param integer $tvseriesId
	 */
	public function get($tvseriesId) {
		if (!$this->application->request->isGet()) {
			throw new Exception('Method not allowed', 405);
		}

		$tvSeries = TVSeries::findFirst($tvseriesId);
		if (!$tvSeries) {
			throw new Exception('TVSeries instance not found', 404);
		}

		$bp = BroadcastProgram::findFirst($tvSeries->getBroadcastProgramId());
		if (!$bp) {
			throw new Exception('Parent class not retrieved', 500);
		}

		$bpArr = $bp->toArray();
		unset($bpArr['id']);

		return array('code' => 200, 'content' => array_merge($tvSeries->toArray(), $bpArr));
	}
}