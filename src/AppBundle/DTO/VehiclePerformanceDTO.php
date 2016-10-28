<?php
/**
 * Created by IntelliJ IDEA.
 * User: cuong
 * Date: 27/09/16
 * Time: 09:42
 */

namespace AppBundle\DTO;

class VehiclePerformanceDTO
{
    /**
     * @var int
     */
    public $vehicleId;
    /**
     * @var string
     */
    public $maximumPower;
    /**
     * @var string
     */
    public $co2LevelgKm;
    /**
     * @var string
     */
    public $fuelConsumptionKm;

    public function __construct(int $vehicleId, string $maximumPower, string $co2LevelgKm, string $fuelConsumptionKm)
    {
        $this->vehicleId = $vehicleId;
        $this->maximumPower = $maximumPower;
        $this->co2LevelgKm = $co2LevelgKm;
        $this->fuelConsumptionKm = $fuelConsumptionKm;
    }
}