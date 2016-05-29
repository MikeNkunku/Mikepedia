<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$tvSeriesCollection = new MicroCollection();
$tvSeriesCollection->setHandler('Controllers\TVSeriesController');
$tvSeriesCollection->setPrefix('/api/tvseries');

$tvSeriesCollection->get('/{tvseriesId:[0-9]+}', 'get');
$tvSeriesCollection->post('/', 'add');
$tvSeriesCollection->put('/{tvseriesId:[0-9]+}', 'update');
$tvSeriesCollection->delete('/{tvseriesId:[0-9]+}', 'delete');

$tvSeriesCollection->get('/all', 'getAll');
$tvSeriesCollection->get('/list', 'getValidList');
$tvSeriesCollection->get('/list/{statusName:[a-z]+}', 'getList');
$tvSeriesCollection->get('/{tvseriesId:[0-9]+}/seasons', 'getSeasons');

return $tvSeriesCollection;