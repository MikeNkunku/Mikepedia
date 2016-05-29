<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$seasonCollection = new MicroCollection();
$seasonCollection->setHandler('Controllers\SeasonController');
$seasonCollection->setPrefix('/api/seasons');

$seasonCollection->get('/{seasonId:[0-9]+}', 'get');
$seasonCollection->post('/', 'add');
$seasonCollection->put('/{seasonId:[0-9]+}', 'update');
$seasonCollection->delete('/{seasonId:[0-9]+}', 'delete');

$seasonCollection->get('/all', 'getAll');
$seasonCollection->get('/list', 'getValidList');
$seasonCollection->get('/list/{statusName:[a-z]+}', 'getList');
$seasonCollection->get('/{seasonId:[0-9]+}/episodes/all', 'getEpisodes');

return $seasonCollection;