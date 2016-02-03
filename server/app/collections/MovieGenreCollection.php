<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$movieGenreCollection = new MicroCollection();
$movieGenreCollection->setHandler('Controllers\MovieGenreController', true);
$movieGenreCollection->setPrefix('/api/movies/genres/');

// Define routes
$movieGenreCollection->get('/{movieGenreId:[0-9]+}', 'get');
$movieGenreCollection->post('/', 'add');
$movieGenreCollection->delete('/{movieGenreId:[0-9]+}', 'delete');

return $movieGenreCollection;