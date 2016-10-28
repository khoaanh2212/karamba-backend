<?php
namespace AppBundle\DomainServices;


use AppBundle\Registry\JatoRegistry;

class PacksDomainService
{
    /**
     * @var JatoRegistry
     */
    private $jatoRegistry;

    public function __construct(JatoRegistry $jatoRegistry)
    {
        $this->jatoRegistry = $jatoRegistry;
    }

    public function getPacksForVehicleId(int $vehicleId)
    {
        return $this->jatoRegistry->getPacks($vehicleId);
    }
}