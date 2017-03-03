<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

try {
    // Register the loader component
    $loader = new Loader();
    $loader->registerDirs([
        '../app/controllers/',
        '../app/models/'
    ]);
    $loader->register();

    // Di Container
    $di = new FactoryDefault();

    // Connect with database
    $di->set('db', function() {
       $connect = new PdoMysql([
           "host" => "localhost",
           "username" => "root",
           "password" => "root1234",
           "dbname" => "phonebook"
       ]);
       return $connect;
    });

    // Register the view service
    $di->set('view', function() {
        $view = new View();
        $view->setViewsDir('../app/views');
        return $view;
    });

    // Register the URL service
    $di->set("url", function () {
        $url = new UrlProvider();
        $url->setBaseUri("/phonebook/");
        return $url;
    });

    // handle requests
    $app = new Application($di);
    echo $app->handle()->getContent();

} catch (\Exception $e) {
    echo "Exception ", $e->getMessage();
}