<?php

namespace AppBundle\DomainServices;

use AppBundle\Registry\VehicleRegistry;


class VehicleDomainService
{
    /**
     * @var VehicleRegistry
     */
    private $registry;


    public function __construct(VehicleRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getVehicle(String $brand, String $model)
    {
        return $this->registry->getVehicle($brand, $model);
    }

    public function getPerformances($vehicles)
    {
        $performances = array();
        foreach ($vehicles as $id) {
            array_push($performances, $this->registry->getVehiclePerformance($id));
        }
        return $performances;
    }

    public function getVehicleExtrasAndPicture(string $id, string $packageId = null)
    {
        return $this->registry->getVehicleExtrasAndPicture($id, $packageId);
    }
}
