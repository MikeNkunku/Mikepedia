<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$episodeCollection = new MicroCollection();
$episodeCollection->setHandler('Controllers\EpisodeController');
$episodeCollection->setPrefix('/api/episodes');

$episodeCollection->get('/{episodeId:[0-9]+}', 'get');
$episodeCollection->post('/', 'add');
$episodeCollection->put('/{episodeId:[0-9]+}', 'update');
$episodeCollection->delete('/{episodeId:[0-9]+}', 'delete');

$episodeCollection->get('/all', 'getAll');
$episodeCollection->get('/list', 'getValidList');
$episodeCollection->post('/list/{statusName:[a-z]+}', 'getList');

return $episodeCollection;