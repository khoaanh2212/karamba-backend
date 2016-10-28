<?php

namespace AppBundle\Entity;

class Vehicle
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    
}
