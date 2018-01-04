<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:01
 */


use Classes\Container;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

$container = Container::getContainer();

$container['logger'] = function ($c) {
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
        } else if ($error instanceof \Exception) {
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
if ( ! empty($dbSettings)) {
    foreach ($dbSettings as $dbType => $dbInfo) {
        foreach ($dbInfo as $name => $dbSetting) {
            if ($dbType === "mysql") {
                $container["mysql-$name"] = function ($c) use ($dbSetting) {
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
