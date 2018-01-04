<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 28.11.2017
 * Time: 16:58
 */

return [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'db' => [
            //Database configurations
            'mysql' => [
                '!!ALIAS!!' => [
                    'host' => "!!HOST_NAME!!",
                    'port' => "!!PORT_NUMBER!!",
                    'user' => "!!DB_USER!!",
                    'pass' => "!!DB_PASSWORD!!",
                    'database' => "!!DB_NAME!!",
                    'timezone' => "asia/baghdad"
                ]
            ],
        ],
        'auth' => [
            'username' => '!!USERNAME!!',
            'password' => '!!PASSWORD!!'
        ],
        'session' => [
            //Session settings
            'name'                          => '!!SESSION_NAME!!',
            'lifetime'                      => 7200,
            'path'                          => null,
            'domain'                        => null,
            'secure'                        => false,
            'httponly'                      => true,
            'updateLifetimeWithEachRequest' => true,
        ],
        'logger' => [
            'name' => '!!LOGGER_NAME!!',
            'path' => __DIR__ . '/../logs/!!app!!.log',
            'level' => \Monolog\Logger::DEBUG,
        ]
    ]
];
