<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$productionTypeCollection = new MicroCollection();
$productionTypeCollection->setHandler('Controllers\ProductionTypeController', true);
$productionTypeCollection->setPrefix('/api/productions/types');

// Define routes
$productionTypeCollection->get('/{productionTypeId:[0-9]+}', 'get');
$productionTypeCollection->post('/', 'add');
$productionTypeCollection->delete('/{productionTypeId:[0-9]+}', 'delete');

return $productionTypeCollection;