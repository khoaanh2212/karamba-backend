<?php

namespace AppBundle\Utils;


class UUIDGeneratorFactory
{
    /**
     * @var UUIDGenerator
     */
    private static $instance;

    public static function getInstance(): UUIDGenerator {
        if(!self::$instance) {
            self::$instance = new UUIDGenerator();
        }
        return self::$instance;
    }

    public static function setInstance(UUIDGenerator $generator) {
        self::$instance = $generator;
    }

    public static function reset()
    {
        self::$instance = null;
    }

}