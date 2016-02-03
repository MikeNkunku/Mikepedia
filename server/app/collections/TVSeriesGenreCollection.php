<?php

namespace Collections;

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup Collection
$tvSeriesGenreCollection = new MicroCollection();
$tvSeriesGenreCollection->setHandler('Controllers\TVSeriesGenreController', true);
$tvSeriesGenreCollection->setPrefix('/api/tvseries/genres/');

// Define routes
$tvSeriesGenreCollection->get('/{tvSeriesGenreId:[0-9]+}', 'get');
$tvSeriesGenreCollection->post('/', 'add');
$tvSeriesGenreCollection->delete('/{tvSeriesGenreId:[0-9]+}', 'delete');

return $tvSeriesGenreCollection;