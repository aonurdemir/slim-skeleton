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

$app->get('/', function (/** @noinspection PhpUnusedParameterInspection */
    Request $req, Response $res, $args = []) {
    /**@var DocumentManager*/
    $dm = \Classes\Container::getContainer()->get("dm");


    $device = new Device();
    $device->setAlias("HTC Telefonum");
    $device->addBluetoothId("2a");
    $device->addBluetoothId("2i");
    $device->setAssetType("Telefon");
    $device->setCreatedAt(new DateTime());
    $device->setLastLocation(new Classes\Odm\Documents\Embedded\Coordinates(12.0, 12.0));
    $device->setUpdatedAt(new DateTime());
    $device->setVersion("v0.0.1");

    $user = new User();
    $user->setCreatedAt(new DateTime());
    $user->setFirstName("Onur");
    $user->setLastName("Demir");
    $user->setUsername("denizemir");

    $device->setOwner($user);

    $dm->persist($device);
    $dm->persist($user);
    $dm->flush();

    $res = $this->view->render($res, "index.phtml");
    return $res;




//    $employee = new Employee();
//    $employee->setName('Employee');
//    $employee->setSalary(50000);
//    $employee->setStarted(new DateTime());
//
//    $address = new Address();
//    $address->setAddress('555 Doctrine Rd.');
//    $address->setCity('Nashville');
//    $address->setState('TN');
//    $address->setZipcode('37209');
//    $employee->setAddress($address);
//
//    $project = new Project('New Project');
//    $manager = new Manager();
//    $manager->setName('Manager');
//    $manager->setSalary(100000);
//    $manager->setStarted(new DateTime());
//    $manager->addProject($project);
//
//    $dm->persist($employee);
//    $dm->persist($address);
//    $dm->persist($project);
//    $dm->persist($manager);
//    $dm->flush();
//
//    $res = $this->view->render($res, "index.phtml");
//    return $res;


    if (! Auth::isAuthenticated()) {
        return $res->withRedirect("/login");
    }

    $res = $this->view->render($res, "index.phtml");
    return $res;
});
