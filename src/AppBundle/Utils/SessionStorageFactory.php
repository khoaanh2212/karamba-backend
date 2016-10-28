<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 4/07/16
 * Time: 17:23
 */

namespace AppBundle\Utils;


use Symfony\Component\HttpFoundation\Session\Session;

class SessionStorageFactory
{
    /**
     * @var SessionStorage
     */
    private static $instance;

    public static function getInstance(): SessionStorage {
        if(!self::$instance) {
            $session = new Session();
            self::$instance = new SessionStorage($session);
        }
        return self::$instance;
    }

    public static function setInstance(SessionStorage $storage) {
        self::$instance = $storage;
    }
}