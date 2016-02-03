<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$gameGenreCollection = new MicroCollection();
$gameGenreCollection->setHandler('Controllers\GameGenreController', true);
$gameGenreCollection->setPrefix('/api/games/genres');

// Define routes
$gameGenreCollection->get('/{gameGenreId:[0-9]+}', 'get');
$gameGenreCollection->post('/', 'add');
$gameGenreCollection->delete('/{gameGenreId:[0-9]+}', 'delete');

return $gameGenreCollection;