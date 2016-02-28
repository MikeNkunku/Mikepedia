<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$mangaCollection = new MicroCollection();
$mangaCollection->setHandler('Controllers\MangaController');
$mangaCollection->setPrefix('/api/mangas');

$mangaCollection->get('/{mangaId:[0-9]+}', 'get');
$mangaCollection->post('/', 'add');
$mangaCollection->put('/{mangaId:[0-9]+}', 'update');
$mangaCollection->delete('/{mangaId:[0-9]+}', 'delete');

$mangaCollection->get('/all', 'getAll');
$mangaCollection->get('/list', 'getValidList');
$mangaCollection->get('/list/{statusName:w+}', 'getList');

return $mangaCollection;