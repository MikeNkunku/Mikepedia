<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$celebrityCollection = new MicroCollection();
$celebrityCollection->setHandler('Controllers\CelebrityController');
$celebrityCollection->setPrefix('/api/celebrities');

$celebrityCollection->get('/{celebrityId:[0-9]+}', 'get');
$celebrityCollection->post('/', 'add');
$celebrityCollection->put('/{celebrityId:[0-9]+}', 'update');
$celebrityCollection->delete('/{celebrityId:[0-9]+}', 'delete');

$celebrityCollection->get('/all', 'getAll');
$celebrityCollection->get('/list', 'getValidList');
$celebrityCollection->post('/list/{statusName:w+}', 'getList');

return $celebrityCollection;