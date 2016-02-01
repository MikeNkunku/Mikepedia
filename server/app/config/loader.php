<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Registration of the namespaces
 */
$loader->registerNamespaces(
    $config->app->namespaces->toArray()
)->register();