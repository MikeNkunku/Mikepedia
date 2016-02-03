<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$broadcastTypeCollection = new MicroCollection();
$broadcastTypeCollection->setHandler('Controllers\BroadcastTypeController', true);
$broadcastTypeCollection->setPrefix('/api/broadcasttypes');

// Define routes
$broadcastTypeCollection->get('/{broadcastTypeId:[0-9]+}', 'get');
$broadcastTypeCollection->post('/', 'add');
$broadcastTypeCollection->delete('/{broadcastTypeId:[0-9]+}', 'delete');

return $broadcastTypeCollection;