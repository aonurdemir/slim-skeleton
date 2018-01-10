<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:05
 */


use Classes\Authentication\Auth;


use Classes\Odm\Documents\Device;


use Classes\Odm\Documents\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Slim\Http\Request;
use Slim\Http\Response;

require_once __DIR__ . '/authServerRoutes.php';

$app->get('/logout', function (/** @noinspection PhpUnusedParameterInspection */
    Request $req, Response $res, $args = []) {
    Auth::logout();
    return $res->withRedirect("/");
});

$app->get('/login', function (Request $req, Response $res, /** @noinspection PhpUnusedParameterInspection */
                              $args = []) {
    if (array_key_exists('auth', $req->getQueryParams())) {
        $auth = $req->getQueryParams()['auth'];
    }
    $res  = $this->view->render($res, "login.phtml", ["auth" => $auth]);
    return $res;
});

$app->post('/login', function (Request $req, Response $res, /** @noinspection PhpUnusedParameterInspection */
                               $args = []) {
    $username = $req->getParsedBody()['username'];
    $password = $req->getParsedBody()['password'];

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    if (Auth::credentialsValid($username, $password)) {
        Auth::login();
        return $res->withRedirect("/");
    } else {
        return $res->withRedirect("/login?auth=0");
    }
});

$app->get('/', function (Request $req, Response $res, $args = []) {
    if (! Auth::isAuthenticated()) {
        return $res->withRedirect("/login");
    }

    $res = $this->view->render($res, "index.phtml");
    return $res;
});
