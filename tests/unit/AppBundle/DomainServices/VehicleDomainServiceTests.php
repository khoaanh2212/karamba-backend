<?php


use AppBundle\DomainServices\VehicleDomainService;
use AppBundle\Registry\VehicleRegistry;


class VehicleDomainServiceTests extends PHPUnit_Framework_TestCase
{

    const BRAND = "BMW";
    const MODEL = "1 Series";
    const ID = 1;

    /**
     * @var VehicleDomainService
     */
    private $sut;

    /**
     * @var VehicleRegistry
     */
    private $registry;


    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\VehicleRegistry")->disableOriginalConstructor()->setMethods(
            array("getVehicle", "getVehicleExtras")
        )->getMock();
        $this->sut = new VehicleDomainService($this->registry);
    }

    public function test_getVehicle_callsRegistryWithBrandAndModel()
    {
        $this->registry->expects($this->exactly(1))->method("getVehicle");
        $this->sut->getVehicle(self::BRAND, self::MODEL);
    }
    public function test_getVehicleExtras_callsRegistryWithId()
    {
        $this->registry->expects($this->exactly(1))->method("getVehicleExtras");
        $this->sut->getVehicleExtras(self::ID);
    }
}