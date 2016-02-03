<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$gamePlatformCollection = new MicroCollection();
$gamePlatformCollection->setHandler('Controllers\GamePlatformController', true);
$gamePlatformCollection->setPrefix('/api/games/platforms');

// Define routes
$gamePlatformCollection->get('/{gamePlatformId:[0-9]+}', 'get');
$gamePlatformCollection->post('/', 'add');
$gamePlatformCollection->delete('/{gamePlatformId:[0-9]+}', 'delete');

return $gamePlatformCollection;