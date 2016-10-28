<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 14/09/16
 * Time: 11:38
 */

namespace AppBundle\Factory;


use AppBundle\DTO\ExtrasDTO;
use AppBundle\Entity\CarAppliance;

class CarApplianceFactory
{
    public function constructCarAppliance(string $clientId, int $vehicleId, string $brand, string $model, string $derivative, int $numberOfDoors, $transmission, $motorType, float $price, string $image, array $extras = null, ExtrasDTO $package = null, ExtrasDTO $color = null): CarAppliance
    {
        return new CarAppliance($clientId, $vehicleId, $brand, $model, $derivative, $numberOfDoors, $transmission, $motorType, $price, $image, $extras, $package, $color);
    }
}