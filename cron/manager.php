<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 29.11.2017
 * Time: 17:53
 */


use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use Classes\Container;
use Monolog\ErrorHandler;
use Monolog\Logger;

ini_set("memory_limit", "2G");
ini_set('max_execution_time', 60 * 60 * 3); //3hours

date_default_timezone_set('asia/baghdad');
setlocale(LC_ALL, 'en_US.UTF-8');
mb_internal_encoding("UTF-8");
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(__DIR__ . '/../') . DS);
define('APP_PATH', ROOT . 'Classes' . DS);

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(require __DIR__ . '/../config/settings.php');

require __DIR__ . '/../config/dependencies.php';


$handler = new ErrorHandler(Container::getContainer()->get("cron-logger"));
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();


/**@var PDO $db */

$db    = Container::getContainer()->get("mysql-archiveapp");
$sql   = "SELECT * FROM job where running=0 and finished=0";
$tasks = $db->prepare($sql);
$tasks->execute();
$tasks = $tasks->fetchAll();

$rule1 = new InMemoryRule();
$rule1->setProcesses(3);
$rule1->setMinimumProcessorUsage(0);
$rule1->setMaximumProcessorUsage(100);

$manager = new ProcessManager();
//stdout path, real log is above in the container
$manager->setLogPath(__DIR__ . '/../cron-log');

$manager->setWorker(__DIR__ . "/../cron/consumer.php");


$sql = "UPDATE job SET time_started = FROM_UNIXTIME(:t), running=1 where id= :id";
foreach ($tasks as $row) {
    $task = @unserialize(base64_decode($row["task"]));
    $manager->addTask($task);

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":t", time(), PDO::PARAM_STR);
    $stmt->bindValue(":id", $row["id"], PDO::PARAM_INT);
    $stmt->execute();
}

$manager->addRule($rule1);
try {
    while ($manager->tick()) {
        usleep(0.5);
    }
} catch (Exception $e) {
    $sql = "UPDATE job SET time_started = null, running=0 where id= :id";
    foreach ($tasks as $row) {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":t", time(), PDO::PARAM_STR);
        $stmt->bindValue(":id", $row["id"], PDO::PARAM_INT);
        $stmt->execute();
    }
    /**@var Logger $logger*/
    $logger = Container::getContainer()->get("cron-logger");
    $logger->critical($e->getMessage(), $e->getTrace());
    exit;
}

$sql = "UPDATE job SET time_finished = FROM_UNIXTIME(:t), running=0, finished=1 where id= :id";
foreach ($tasks as $row) {
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":t", time(), PDO::PARAM_STR);
    $stmt->bindValue(":id", $row["id"], PDO::PARAM_INT);
    $stmt->execute();
}

/**@var Logger $logger*/
$logger = Container::getContainer()->get("cron-logger");
$logger->info(sprintf("Executed and finished gracefully with %s tasks", count($tasks)));
