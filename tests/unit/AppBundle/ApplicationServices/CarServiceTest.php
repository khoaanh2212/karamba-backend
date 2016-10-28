<?php


use AppBundle\ApplicationServices\CarService;
use AppBundle\DomainServices\CarDomainService;
use AppBundle\DomainServices\VehicleDomainService;


class CarServiceTest extends PHPUnit_Framework_TestCase
{

    const BRAND = "Audi";
    const MODEL = "A4";

    /**
     * @var CarService
     */
    private $sut;
    /**
     * @var CarDomainService
     */
    private $carDomainService;
    /**
     * @var VehicleDomainService
     */
    private $vehicleDomainService;

    protected function setUp()
    {
        $this->carDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\CarDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->vehicleDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\VehicleDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->sut = new CarService($this->carDomainService, $this->vehicleDomainService);
    }

    public function test_getVehicle_callsDomainService()
    {
        $this->vehicleDomainService->expects($this->exactly(1))->method("getVehicle");
        $this->sut->getVehicle(self::BRAND, self::MODEL);
    }
    public function test_getVehicleExtrasAndPicture_callsDomainService()
    {
        $this->vehicleDomainService->expects($this->exactly(1))->method("getVehicleExtrasAndPicture");
        $this->sut->getVehicleExtrasAndPicture(1);
    }
}
