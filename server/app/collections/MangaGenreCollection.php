<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$mangaGenreCollection = new MicroCollection();
$mangaGenreCollection->setHandler('Controllers\MangaGenreController', true);
$mangaGenreCollection->setPrefix('/api/broadcasttypes');

// Define routes
$mangaGenreCollection->get('/{mangaGenreId:[0-9]+}', 'get');
$mangaGenreCollection->post('/', 'add');
$mangaGenreCollection->delete('/{mangaGenreId:[0-9]+}', 'delete');

return $mangaGenreCollection;