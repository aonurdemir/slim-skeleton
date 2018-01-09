<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:01
 */


use Classes\Container;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

$container = Container::getContainer();

$container['logger'] = function (/** @noinspection PhpUnusedParameterInspection */ $c) {
    $loggerSettings = Container::getContainer()->get("settings")["logger"];
    $logger = new Logger($loggerSettings["name"]);

    $logger->pushProcessor(new WebProcessor());
    $logger->pushProcessor(new MemoryUsageProcessor());
    $logger->pushProcessor(new MemoryPeakUsageProcessor());

    $rotatingFileHandler = new RotatingFileHandler($loggerSettings["path"], 14, $loggerSettings["level"]);
    $rotatingFileHandler->setFormatter(new LineFormatter(LineFormatter::SIMPLE_FORMAT . PHP_EOL, null, true, false)); //Enable inline line breaks
    $logger->pushHandler($rotatingFileHandler);
    return $logger;
};

$handler = new ErrorHandler($container->get("logger"));
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();

/**
 * This dependency is required to log errors via monolog which are catched by slim application. Slim displays on screen
 * or writes to php error log according to displayErrorDetails flag in settings. The ones that are not catched by slim
 * application are handled by Monolog\ErrorHandler above.
*/
$container['errorHandler'] = function ($c) {
    return function (Psr\Http\Message\ServerRequestInterface $request, Psr\Http\Message\ResponseInterface $response, Throwable $error) use ($c) {
        /**
         * @var \Slim\Container $c
         * @var \Monolog\Logger $logger
         */

        $logger = $c->get('logger');
        $text = sprintf('Type: %s' . PHP_EOL, get_class($error));
        if (($code = $error->getCode())) {
            $text .= sprintf('Code: %s' . PHP_EOL, $code);
        }
        if (($message = $error->getMessage())) {
            $text .= sprintf('Message: %s' . PHP_EOL, htmlentities($message));
        }
        if (($file = $error->getFile())) {
            $text .= sprintf('File: %s' . PHP_EOL, $file);
        }
        if (($line = $error->getLine())) {
            $text .= sprintf('Line: %s' . PHP_EOL, $line);
        }
        if (($trace = $error->getTraceAsString())) {
            $text .= sprintf('Trace: %s', $trace);
        }
        if ($error instanceof \Error) {
            $logger->error($text);
        } elseif ($error instanceof \Exception) {
            $logger->warning($text);
        } else {
            $logger->notice($text);
        }
        $errorHandler = new \Slim\Handlers\PhpError($c->get('settings')['displayErrorDetails']);
        return $errorHandler($request, $response, $error);
    };
};

$container['phpErrorHandler'] = function ($c) {
    /**
     * @var Slim\Container $c
     */
    return $c->get('errorHandler');
};




$dbSettings = $container->get("settings")["db"];
if (! empty($dbSettings)) {
    foreach ($dbSettings as $dbType => $dbInfo) {
        foreach ($dbInfo as $name => $dbSetting) {
            if ($dbType === "mysql") {
                $container["mysql-$name"] = function (/** @noinspection PhpUnusedParameterInspection */ $c) use ($dbSetting) {
                    $dbhost   = $dbSetting['host'];
                    $dbuser   = $dbSetting['user'];
                    $dbpass   = $dbSetting['pass'];
                    $dbport   = $dbSetting['port'];
                    $database = $dbSetting['database'];
                    $timezone = $dbSetting['timezone'];

                    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

                    $db = new PDO("mysql:host=$dbhost;dbname=$database;port=$dbport;charset=utf8", $dbuser, $dbpass, $options);
                    $db->query("SET NAMES utf8");
                    $db->query("SET time_zone= \"$timezone\"");

                    return $db;
                };
            }
        }
    }
}


$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

$container['dm'] = function (/** @noinspection PhpUnusedParameterInspection */ $c) use ($autoloader) {
    return require __DIR__.'/odm-config.php';
};
