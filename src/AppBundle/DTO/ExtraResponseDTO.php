<?php
namespace AppBundle\DTO;


class ExtraResponseDTO
{
    public $extras;

    public $colors;

    public function __construct()
    {
        $this->extrasInPacks = array();
        $this->extrasRequired = array();
        $this->extras = array();
        $this->colors = array();
    }

    public function addColor(VehicleOptionDTO $extra)
    {
        array_push($this->colors, $extra);
    }



    public function setExtras(array $extras)
    {
        $this->extras = $extras;
    }
}