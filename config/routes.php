<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 17:05
 */


use Classes\Authentication\Auth;

use Classes\Odm\Documents\Address;
use Classes\Odm\Documents\Employee;
use Classes\Odm\Documents\Manager;
use Classes\Odm\Documents\Project;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/logout', function (Request $req, Response $res, $args = []) {
    Auth::logout();
    return $res->withRedirect("/");
});

$app->get('/login', function (Request $req, Response $res, $args = []) {
    if (array_key_exists('auth', $req->getQueryParams())) {
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

    $dm = \Classes\Container::getContainer()->get("dm");

    $employee = new Employee();
    $employee->setName('Employee');
    $employee->setSalary(50000);
    $employee->setStarted(new DateTime());

    $address = new Address();
    $address->setAddress('555 Doctrine Rd.');
    $address->setCity('Nashville');
    $address->setState('TN');
    $address->setZipcode('37209');
    $employee->setAddress($address);

    $project = new Project('New Project');
    $manager = new Manager();
    $manager->setName('Manager');
    $manager->setSalary(100000);
    $manager->setStarted(new DateTime());
    $manager->addProject($project);

    $dm->persist($employee);
    $dm->persist($address);
    $dm->persist($project);
    $dm->persist($manager);
    $dm->flush();

    $res = $this->view->render($res, "index.phtml");
    return $res;


    if (! Auth::isAuthenticated()) {
        return $res->withRedirect("/login");
    }

    $res = $this->view->render($res, "index.phtml");
    return $res;
});
