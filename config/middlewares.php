<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:03
 */

use Classes\OAuthServer\Repositories\AccessTokenRepository;
use Classes\Session\SessionMiddleware;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;

//$app->add(new SessionMiddleware($app->getContainer()->get('settings')['session']));

// Init our repositories
$accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
// Path to authorization server's public key
$publicKeyPath = 'file://' . __DIR__ . '/../public.key';
// Setup the authorization server
$server = new \League\OAuth2\Server\ResourceServer(    $accessTokenRepository,    $publicKeyPath);

//$app->add(new ResourceServerMiddleware($server));


