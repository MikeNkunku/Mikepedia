<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$mangaCharacterCollection = new MicroCollection();
$mangaCharacterCollection->setHandler('Controllers\MangaCharacterController');
$mangaCharacterCollection->setPrefix('/api/mangas/characters');

$mangaCharacterCollection->get('/{mangaCharacterId:[0-9]+}', 'get');
$mangaCharacterCollection->post('/', 'add');
$mangaCharacterCollection->put('/{mangaCharacterId:[0-9]+}', 'update');
$mangaCharacterCollection->delete('/{mangaCharacterId:[0-9]+}', 'delete');

$mangaCharacterCollection->get('/all', 'getAll');
$mangaCharacterCollection->get('/list', 'getValidList');
$mangaCharacterCollection->get('/list/{statusName:[a-z]+}', 'getList');

return $mangaCharacterCollection;