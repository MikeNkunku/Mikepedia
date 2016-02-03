<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$fictionalCharacterTypeCollection = new MicroCollection();
$fictionalCharacterTypeCollection->setHandler('Controllers\FictionalCharacterTypeController', true);
$fictionalCharacterTypeCollection->setPrefix('/api/fictionalcharactertypes');

// Define routes
$fictionalCharacterTypeCollection->get('/{fictionalCharacterTypeId:[0-9]+}', 'get');
$fictionalCharacterTypeCollection->post('/', 'add');
$fictionalCharacterTypeCollection->delete('/{fictionalCharacterTypeId:[0-9]+}', 'delete');

return $fictionalCharacterTypeCollection;