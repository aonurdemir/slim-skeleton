<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 16:55
 */

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;
use Classes\Container;
use Monolog\ErrorHandler;

ini_set("memory_limit", "2G");
ini_set('max_execution_time', 60 * 60 * 3); //3hours

require __DIR__ . '/../config/projectConfiguration.php';


//configure and load slim app
require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(require __DIR__ . '/../config/settings.php');
require __DIR__ . '/../config/dependencies.php';

//global error handling
$handler = new ErrorHandler(Container::getContainer()->get("cron-logger"));
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();

//script
//
//
if (count($argv) < 2) {
    throw new InvalidArgumentException("Invalid call");
}

$script = array_shift($argv);

$task = array_shift($argv);

/**
 * We must account for the input data being malformed. That's why we use "@".
 */

$task = @unserialize(base64_decode($task));

if ($task instanceof Task) {
    $handler = $task->getHandler();

    $object = new $handler();

    if ($object instanceof Handler) {
        $object->handle($task);
    }
}
