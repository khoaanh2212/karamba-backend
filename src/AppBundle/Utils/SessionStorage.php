<?php

namespace AppBundle\Utils;


use Symfony\Component\HttpFoundation\Session\Session;

class SessionStorage
{
    /**
     * @var Session
     */
    private $storage;

    public function __construct(Session &$storage)
    {
        $this->storage = &$storage;
    }

    public function setValue(string $key, $value)
    {
        $this->storage->set($key, $value);
    }

    public function getValue(string $key)
    {
        return $this->storage->get($key);
    }

    public function removeValue(string $key)
    {
        $this->storage->remove($key);
    }
}