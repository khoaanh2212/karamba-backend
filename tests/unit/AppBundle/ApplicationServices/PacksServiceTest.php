<?php


use AppBundle\ApplicationServices\PacksService;
use AppBundle\DomainServices\PacksDomainService;

class PacksServiceTest extends PHPUnit_Framework_TestCase
{
    const VEHICLE_ID = 1;
    /**
     * @var PacksService
     */
    private $sut;

    /**
     * @var PacksDomainService
     */
    private $packsDomainService;

    protected function setUp()
    {
        $this->packsDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\PacksDomainService")->disableOriginalConstructor()->getMock();
        $this->sut = new PacksService($this->packsDomainService);
    }

    public function test_getPacksForVehicleId_callDomainServicegetPacksForVehicleId()
    {
        $this->packsDomainService->expects($this->once())->method("getPacksForVehicleId");
        $this->sut->getPacksForVehicleId(self::VEHICLE_ID);
    }

    public function test_getPacksForVehicleId_willReturnPacksForVehicleId()
    {
        $packsForVehicleId = array();
        $this->packsDomainService->expects($this->any())->method("getPacksForVehicleId")->will($this->returnValue($packsForVehicleId));
        $this->assertEquals($packsForVehicleId, $this->sut->getPacksForVehicleId(self::VEHICLE_ID));
    }
}