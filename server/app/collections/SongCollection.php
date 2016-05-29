<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$songCollection = new MicroCollection();
$songCollection->setHandler('Controllers\SongController');
$songCollection->setPrefix('/api/songs');

$songCollection->get('/{songId:[0-9]+}', 'get');
$songCollection->post('/', 'add');
$songCollection->put('/{songId:[0-9]+}', 'update');
$songCollection->delete('/{songId:[0-9]+}', 'delete');

$songCollection->get('/all', 'getAll');
$songCollection->get('/list', 'getValidList');
$songCollection->post('/list/{statusName:[a-z]+}', 'getList');

return $songCollection;