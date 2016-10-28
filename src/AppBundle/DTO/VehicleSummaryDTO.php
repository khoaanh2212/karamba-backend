<?php

namespace AppBundle\DTO;

class VehicleSummaryDTO{

    /**
     * @var VehicleDTO[]
     */
    public $vehicles;
    /**
     * @var string[]
     */
    public $fuels;
    /**
     * @var string[]
     */
    public $trans;
    /**
     * @var string[]
     */
    public $doors;

    /**
     * VehicleSummaryDTO constructor.
     * @param VehicleDTO[] $vehicles
     * @param \string[] $fuels
     * @param \string[] $gearBox
     * @param \string[] $doors
     */
    public function __construct(array $vehicles, array $fuels, array $trans, array $doors)
    {
        $this->vehicles = $vehicles;
        $this->fuels = $fuels;
        $this->trans = $trans;
        $this->doors = $doors;
    }
    
}