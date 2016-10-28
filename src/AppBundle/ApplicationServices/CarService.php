<?php

namespace AppBundle\ApplicationServices;

use AppBundle\DomainServices\CarDomainService;
use AppBundle\DomainServices\VehicleDomainService;


class CarService
{
    /**
     * @var CarDomainService
     */
    private $carDomainService;
    /**
     * @var VehicleDomainService
     */
    private $vehicleDomainService;

    public function __construct(CarDomainService $carDomainService, VehicleDomainService $vehicleDomainService)
    {
        $this->carDomainService = $carDomainService;
        $this->vehicleDomainService = $vehicleDomainService;
    }

    public function getBrands()
    {
        return $this->carDomainService->getBrands();
    }

    public function getBrandModels(string $brand)
    {
        return $this->carDomainService->getBrandsModels(array($brand))[$brand];
    }

    public function getVehicle(string $brand, string $model)
    {
        return $this->vehicleDomainService->getVehicle($brand, $model);
    }

    public function getPerformances($vehicles)
    {
        return $this->vehicleDomainService->getPerformances($vehicles);
    }

    public function getVehicleExtrasAndPicture(string $id, string $packageId = null)
    {
        return $this->vehicleDomainService->getVehicleExtrasAndPicture($id, $packageId);
    }
}
