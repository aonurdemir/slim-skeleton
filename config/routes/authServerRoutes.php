<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 4.01.2018
 * Time: 16:56
 */

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$app->post(
    '/access_token',
    function (ServerRequestInterface $request, ResponseInterface $response) use ($app) {
        /* @var \League\OAuth2\Server\AuthorizationServer $server */
        $server = $app->getContainer()->get(AuthorizationServer::class);
        try {
            // Try to respond to the access token request
            return $server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            // All instances of OAuthServerException can be converted to a PSR-7 response
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            // Catch unexpected exceptions
            $body = $response->getBody();
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }
    }
);
