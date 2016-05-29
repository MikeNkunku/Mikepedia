<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$productionCollection = new MicroCollection();
$productionCollection->setHandler('Controllers\ProductionController');
$productionCollection->setPrefix('/api/productions');

$productionCollection->get('/{productionId:[0-9]+}', 'get');
$productionCollection->post('/', 'add');
$productionCollection->put('/{productionId:[0-9]+}', 'update');
$productionCollection->delete('/{productionId:[0-9]+}', 'delete');

$productionCollection->get('/all', 'getAll');
$productionCollection->get('/list', 'getValidList');
$productionCollection->get('/list/{statusName:[a-z]+}', 'getList');
$productionCollection->get('/{productionId:[0-9]+}/songs/all', 'getSongs');

return $productionCollection;