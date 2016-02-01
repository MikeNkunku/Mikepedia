<?php

error_reporting(E_ALL);
set_error_handler('error_handler', E_ALL);

use Phalcon\Mvc\Micro;
use Phalcon\Exception;

try {

    /**
     * Read the configuration
     */
    include __DIR__ . '/../app/config/config.php';

    /**
     * Read auto-loader
     */
    include $config->app->baseDir . 'config/loader.php';

    /**
     * Read services
     */
    include $config->app->baseDir . 'config/services.php';

    /**
     * Create the application
     */
    $app = new Micro($di);

    /*
     * Configure HTTP response
     */
    $app->response->setHeader('Access-Control-Allow-Origin', '*');
    $app->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization');

    /*
     * Add collections for routing
     */
    $collections = glob($config->app->collectionsDir . '*.php');
    foreach ($collections as $collection) {
        $app->mount(include_once $collection);
    }

    /**
     * Handle the request
     */
    $app->notFound(function () use ($app) {
        throw new Exception('Not Found', 404);
    });

    $app->after(function() use ($app) {
        $data = $app->getReturnedValue();

        $app->response->setContentType('application/json', 'utf-8');
        $app->response->setStatusCode($data['code'], null);
        $app->response->setJsonContent($data['content']);
        $app->response->send();
    });

    $app->handle();

} catch (Exception $e) {
    $code = ($e->getCode()) ? $e->getCode() : 500;

    $app->response->setStatusCode($code, $e->getMessage());
    $app->response->setContent($e->getMessage());
    $app->response->send();
}

function error_handler($errno, $errStr, $errFile, $errLine) {
    throw new Exception($errStr . ' in ' . $errFile . ' on line ' . $errLine, 500);
}