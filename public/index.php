<?php

use Classes\Container;
use Monolog\ErrorHandler;

require __DIR__ . '/../config/projectConfiguration.php';
require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(require __DIR__ . '/../config/settings.php');
Container::setContainer($app->getContainer());
require __DIR__ . '/../config/dependencies.php';

$handler = new ErrorHandler($app->getContainer()->get("logger"));
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();

require __DIR__ . '/../config/middlewares.php';
require __DIR__ . '/../config/routes.php';

//necessary for fcgi applications
register_shutdown_function(function () {
    if (function_exists("fastcgi_finish_request")) {
        fastcgi_finish_request();
    }
});

$app->run();
