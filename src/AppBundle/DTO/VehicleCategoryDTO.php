<?php


namespace AppBundle\DTO;

class VehicleCategoryDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var VehicleOptionDTO[]
     */
    public $options;

    /**
     * VehicleCategoryDTO constructor.
     * @param string $name
     * @param $options
     */
    public function __construct(string $name, $options)
    {
        $this->name = $name;
        $this->options = $options;
    }


}