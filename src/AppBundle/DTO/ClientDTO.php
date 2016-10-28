<?php

namespace AppBundle\DTO;

class ClientDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $zipCode;

    public function __construct(string $name, string $email, string $zipCode)
    {
        $this->name = $name;
        $this->email = $email;
        $this->zipCode = $zipCode;
    }
}