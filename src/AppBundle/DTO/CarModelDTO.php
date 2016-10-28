<?php

namespace AppBundle\DTO;

class CarModelDTO
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

    public function __construct(string $brand, string $name, string $year)
    {
        $this->brand = $brand;
        $this->name = $name;
        $this->year = $year;
    }

    function comparison(CarModelDTO $b) : bool
    {
    return ($this->brand == $b->brand && $this->name == $b->name && $this->year == $b->year);
    }
}
