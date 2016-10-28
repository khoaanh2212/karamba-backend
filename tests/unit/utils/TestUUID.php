<?php

use AppBundle\Utils\UUIDGenerator;

class TestUUID extends UUIDGenerator
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function generateId() : string {
        return $this->id;
    }
}