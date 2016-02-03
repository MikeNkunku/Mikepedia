<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$mangaPublicCollection = new MicroCollection();
$mangaPublicCollection->setHandler('Controllers\MangaPublicController', true);
$mangaPublicCollection->setPrefix('/api/mangas/publics');

// Define routes
$mangaPublicCollection->get('/{mangaPublicId:[0-9]+}', 'get');
$mangaPublicCollection->post('/', 'add');
$mangaPublicCollection->delete('/{mangaPublicId:[0-9]+}', 'delete');

return $mangaPublicCollection;