<?php


namespace AppBundle\Utils;


class Cypher
{
    /**
     * @var Cypher
     */
    private static $instance;

    public function encrypt(string $text): string
    {
        return password_hash($text, PASSWORD_DEFAULT);
    }

    public function verify(string $text, string $hashed): bool
    {
        return password_verify($text, $hashed);
    }

    public static function setInstance(Cypher $instance)
    {
        self::$instance = $instance;
    }

    public static function reset()
    {
        self::$instance = null;
    }

    public static function getInstance(): Cypher
    {
        if(!self::$instance) {
            self::$instance = new Cypher();
        }
        return self::$instance;
    }
}