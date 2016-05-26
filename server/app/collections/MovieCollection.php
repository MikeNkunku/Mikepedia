<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$movieCollection = new MicroCollection();
$movieCollection->setHandler('Controllers\MovieController');
$movieCollection->setPrefix('/api/movies');

$movieCollection->get('/{movieId:[0-9]+}', 'get');
$movieCollection->post('/', 'add');
$movieCollection->put('/{movieId:[0-9]+}', 'update');
$movieCollection->delete('/{movieId:[0-9]+}', 'delete');

$movieCollection->get('/all', 'getAll');
$movieCollection->get('/list', 'getValidList');
$movieCollection->post('/list/{statusName:[a-z]+}', 'getList');

return $movieCollection;