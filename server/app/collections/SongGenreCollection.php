<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$songGenreCollection = new MicroCollection();
$songGenreCollection->setHandler('Controllers\SongGenreController', true);
$songGenreCollection->setPrefix('/api/songs/genres/');

// Define routes
$songGenreCollection->get('/{songGenreId:[0-9]+}', 'get');
$songGenreCollection->post('/', 'add');
$songGenreCollection->delete('/{songGenreId:[0-9]+}', 'delete');

return $songGenreCollection;