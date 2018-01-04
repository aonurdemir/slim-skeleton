<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:05
 */

use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use Classes\Authentication\Auth;
use Classes\Report\DailyReport;
use Classes\Report\MonthlyReport;
use Classes\Task\ReportTask;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/logout', function (Request $req, Response $res, $args = []) {
    Auth::logout();
    return $res->withRedirect("/");
});

$app->get('/login', function (Request $req, Response $res, $args = []) {
    if(array_key_exists('auth',$req->getQueryParams())){
        $auth = $req->getQueryParams()['auth'];
    }
    $res  = $this->view->render($res, "login.phtml", ["auth" => $auth]);
    return $res;
});

$app->post('/login', function (Request $req, Response $res, $args = []) {

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
    if ( ! Auth::isAuthenticated()) {
        return $res->withRedirect("/login");
    }

    $res = $this->view->render($res, "index.phtml");
    return $res;
});

