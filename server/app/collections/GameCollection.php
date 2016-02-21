<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$gameCollection = new MicroCollection();
$gameCollection->setHandler('Controllers\GameController');
$gameCollection->setPrefix('/api/games');

$gameCollection->get('/{gameId:[0-9]+}', 'get');
$gameCollection->post('/', 'add');
$gameCollection->put('/{gameId:[0-9]+}', 'update');
$gameCollection->delete('/{gameId:[0-9]+}', 'delete');

$gameCollection->get('/all', 'getAll');
$gameCollection->get('/list', 'getValidList');
$gameCollection->get('/list/{statusName:w+}', 'getList');
$gameCollection->get('/genres/{gameGenreId:[0-9]+}', 'getByGenre');
$gameCollection->get('/platforms/{gamePlatformId:[0-9]+}', 'getByPlatform');
$gameCollection->get('/year/{year:[0-9]{4}}', 'getByYear');

return $gameCollection;