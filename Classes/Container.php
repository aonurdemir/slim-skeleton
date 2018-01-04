<?php

namespace Classes;

use Psr\Container\ContainerInterface;

/**
 * Class Container
 * Helper Class for getting the global container. Not to be confused with Slim's Container class.
 */
class Container
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    private static $isSet = false;

    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
        self::$isSet     = true;
    }

    /**
     * @return ContainerInterface
     *
     * @throws \Exception
     */
    public static function getContainer()
    {
        if (self::$isSet) {
            return self::$container;
        }

        throw new \Exception('Container is not set.');
    }
}
