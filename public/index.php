<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Mvc\Router;
use Phalcon\Config;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    $config = new ConfigIni("../app/config/config.ini");

    // Register the loader component
    $loader = new Loader();
    $loader->registerDirs([
        $config->app->controllersDir,
        $config->app->modelsDir
    ]);
    $loader->register();

    // Di Container
    $di = new FactoryDefault();

    // Connect with database
    $di->set('db', function() use ($config) {
       $connect = new PdoMysql($config->database->toArray());
       return $connect;
    });

    // Register the view service
    $di->set('view', function() use ($config) {
        $view = new View();
        $view->setViewsDir($config->app->viewsDir);
        return $view;
    });

    // Register the URL service
    $di->set("url", function () use ($config) {
        $url = new UrlProvider($config->app->baseUri);
        $url->setBaseUri("/");
        return $url;
    });

    // Setup the default route
    $di->set('router', function() use ($config) {
        $router = new Router();
        $router->setDefaultController($config->app->setDefaultController);
        return $router;
    });

    // handle requests
    $app = new Application($di);
    echo $app->handle()->getContent();

} catch (\Exception $e) {
    echo "Exception ", $e->getMessage();
}