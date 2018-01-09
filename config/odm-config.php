<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 8.01.2018
 * Time: 17:23
 */

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

if (! file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run this script.');
}
if (!isset($autoloader)) {
    $autoloader = require_once $file;
}
$loader = $autoloader;
$loader->add('Documents', __DIR__.'/../Classes/Odm');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$connection = new Connection();

$config = new Configuration();
//$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcuCache());
$config->setProxyDir(__DIR__.'/../Classes/Odm' . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__.'/../Classes/Odm' . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('doctrine_odm');
$config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__.'/../Classes/Odm' . '/Documents'));

$dm = DocumentManager::create($connection, $config);

//optional. If it is not desired to ensure indexes for every request, it can be run in console app.
$sm = $dm->getSchemaManager();
$sm->ensureIndexes();

return $dm;
