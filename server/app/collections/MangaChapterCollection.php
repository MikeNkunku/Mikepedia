<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$mangaChapterCollection = new MicroCollection();
$mangaChapterCollection->setHandler('Controllers\MangaChapterController');
$mangaChapterCollection->setPrefix('/api/mangas/chapters');

$mangaChapterCollection->get('/{mangaChapterId:[0-9]+}', 'get');
$mangaChapterCollection->post('/', 'add');
$mangaChapterCollection->put('/{mangaChapterId:[0-9]+}', 'update');
$mangaChapterCollection->delete('/{mangaChapterId:[0-9]+}', 'delete');

$mangaChapterCollection->get('/all', 'getAll');
$mangaChapterCollection->get('/list', 'getValidList');
$mangaChapterCollection->get('/list/{statusName:[a-z]+}', 'getList');

return $mangaChapterCollection;