<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$animeCollection = new MicroCollection();
$animeCollection->setHandler('Controllers\AnimeController');
$animeCollection->setPrefix('/api/animes');

$animeCollection->get('/{animeId:[0-9]+}', 'get');
$animeCollection->post('/', 'add');
$animeCollection->put('/{animeId:[0-9]+}', 'update');
$animeCollection->delete('/{animeId:[0-9]+}', 'delete');

$animeCollection->get('/all', 'getAll');
$animeCollection->get('/list', 'getValidList');
$animeCollection->post('/list/{statusName:[a-z]+}', 'getList');
$animeCollection->get('/{animeId:[0-9]+}/seasons', 'getSeasons');

return $animeCollection;