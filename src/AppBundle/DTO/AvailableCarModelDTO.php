<?php

namespace AppBundle\DTO;

class AvailableCarModelDTO
{
    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $year;

    /**
     * @var bool
     */
    public $available;

    public function __construct(string $brand, string $name, string $year, bool $available)
    {
        $this->brand = $brand;
        $this->name = $name;
        $this->year = $year;
        $this->available = $available;
    }
}
