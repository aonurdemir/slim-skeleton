<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:03
 */

use Classes\Session\SessionMiddleware;

$app->add(new SessionMiddleware($app->getContainer()->get('settings')['session']));
