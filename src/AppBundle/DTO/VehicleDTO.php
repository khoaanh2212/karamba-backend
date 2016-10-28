<?php

namespace AppBundle\DTO;

class VehicleDTO
{
    /**
     * @var int
     */
    public $vehicleId;
    /**
     * @var string
     */
    public $makeKey;
    /**
     * @var string
     */
    public $makeNameToDisplay;
    /**
     * @var string
     */
    public $modelKey;
    /**
     * @var string
     */
    public $modelNameToDisplay;
    /**
     * @var string
     */
    public $modelYearToDisplay;
    /**
     * @var string
     */
    public $fuelType;
    /**
     * @var string
     */
    public $fuelTypeToDisplay;
    /**
     * @var string
     */
    public $derivative;
    /**
     * @var string
     */
    public $transmission;
    /**
     * @var string
     */
    public $numberOfDoorsToDisplay;
    /**
     * @var string
     */
    public $derivativeToDisplay;

    /**
     * @var string
     */
    public $price;

    /**
     * @var VehiclePerformanceDTO
     */
    public $performance;

    /**
     * @var string
     */
    public $priceToDisplay;
    /**
     * VehicleDTO constructor.
     * @param int    $vehicleId
     * @param string $makeKey
     * @param string $makeNameToDisplay
     * @param string $modelKey
     * @param string $modelNameToDisplay
     * @param string $modelYearToDisplay
     * @param string $fuelType
     * @param string $fuelTypeToDisplay
     * @param string $derivative
     * @param string $transmission
     * @param string $numberOfDoorsToDisplay
     * @param string $derivativeToDisplay
     * @param string $priceToDisplay
     * @param string $price
     */
    public function __construct(int $vehicleId, string $makeKey, string $makeNameToDisplay, string $modelKey, string $modelNameToDisplay, string $modelYearToDisplay, string $fuelType, string $fuelTypeToDisplay, string $derivative, string $transmission, string $numberOfDoorsToDisplay, string $derivativeToDisplay, string $price, string $priceToDisplay)
    {
        $this->vehicleId              = $vehicleId;
        $this->makeKey                = $makeKey;
        $this->makeNameToDisplay      = $makeNameToDisplay;
        $this->modelKey               = $modelKey;
        $this->modelNameToDisplay     = $modelNameToDisplay;
        $this->modelYearToDisplay     = $modelYearToDisplay;
        $this->fuelType               = $fuelType;
        $this->fuelTypeToDisplay      = ucfirst($fuelTypeToDisplay);
        $this->derivative             = $derivative;
        $this->transmission           = $this->validTransmission($transmission);
        $this->numberOfDoorsToDisplay = $numberOfDoorsToDisplay;
        $this->derivativeToDisplay    = $derivativeToDisplay;
        $this->price                  = $price;
        $this->priceToDisplay         = $priceToDisplay;
    }

    public function setPerformance(VehiclePerformanceDTO $performance)
    {
        $this->performance = $performance;
    }

    private function validTransmission($transmission){
        switch(strtoupper($transmission)){
            case "A":
                return "Automático";
            case "M":
                return "Manual";
            default:
                return $transmission;
        }
    }
}
