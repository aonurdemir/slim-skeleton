<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 3.01.2018
 * Time: 15:31
 */

//Define timezone and encoding
date_default_timezone_set('asia/baghdad');
setlocale(LC_ALL, 'en_US.UTF-8');
mb_internal_encoding("UTF-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(__DIR__ . '/../') . DS);
define('APP_PATH', ROOT . 'Classes' . DS);
