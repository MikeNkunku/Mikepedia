<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

$lyricsCollection = new MicroCollection();
$lyricsCollection->setHandler('Controllers\LyricsController');
$lyricsCollection->setPrefix('/api/lyrics');

$lyricsCollection->get('/{lyricsId:[0-9]+}', 'get');
$lyricsCollection->post('/', 'add');
$lyricsCollection->put('/{lyricsId:[0-9]+}', 'update');
$lyricsCollection->delete('/{lyricsId:[0-9]+}', 'delete');

$lyricsCollection->get('/all', 'getAll');
$lyricsCollection->get('/list', 'getValidList');
$lyricsCollection->get('/list/{statusName:w+}', 'getList');

return $lyricsCollection;