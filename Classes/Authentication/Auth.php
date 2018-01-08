<?php
/**
 * Created by PhpStorm.
 * User: umutcanguney
 * Date: 14/02/2017
 * Time: 16:30
 */

namespace Classes\Authentication;

use Classes\Container;
use Classes\Session;

class Auth
{
    public static function credentialsValid(string $username, string $password) : bool
    {
        if ($username === Container::getContainer()->get("settings")["auth"]["username"] && $password === Container::getContainer()->get("settings")["auth"]["password"]) {
            return true;
        }
        Session::delete("loggedIn");
        return false;
    }

    public static function isAuthenticated()
    {
        if (Session::get('loggedIn') === true) {
            return true;
        }
        return false;
    }

    public static function login()
    {
        Session::regenerate(true);
        Session::set("loggedIn", true);
        return true;
    }

    public static function logout()
    {
        Session::delete("loggedIn");
        Session::destroy();
        Session::start();
        Session::regenerate(true);
    }
}
