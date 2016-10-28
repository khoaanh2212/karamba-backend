<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 7/09/16
 * Time: 14:30
 */

namespace AppBundle\ApplicationServices;


use AppBundle\DomainServices\PacksDomainService;

class PacksService
{
    /**
     * @var PacksDomainService
     */
    private $packsDomainService;

    public function __construct(PacksDomainService $packsDomainService)
    {
        $this->packsDomainService = $packsDomainService;
    }

    public function getPacksForVehicleId(int $vehicleId)
    {
        return $this->packsDomainService->getPacksForVehicleId($vehicleId);
    }
}