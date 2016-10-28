<?php


use AppBundle\DomainServices\PacksDomainService;
use AppBundle\DTO\PackItemDTO;
use AppBundle\Registry\JatoRegistry;

class PacksDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const VEHICLE_ID = 1;
    /**
     * @var PacksDomainService
     */
    private $sut;

    /**
     * @var JatoRegistry
     */
    private $jatoRegistry;

    protected function setUp()
    {
        $this->jatoRegistry = $this->getMockBuilder("AppBundle\\Registry\\JatoRegistry")->disableOriginalConstructor()->getMock();
        $this->sut = new PacksDomainService($this->jatoRegistry);
    }

    public function test_getPacksForVehicleIdWithTheVehicleIdShouldCallToGetPacks()
    {
        $this->jatoRegistry->expects($this->once())->method("getPacks")->with(self::VEHICLE_ID);
        $this->sut->getPacksForVehicleId(self::VEHICLE_ID);
    }

    public function test_getPacksForVehicleIdShouldReturnTheValueFromRegistryGetPacks()
    {
        $this->jatoRegistry->expects($this->any())->method("getPacks")->will($this->returnValue(array(new PackItemDTO(1, "testName", array(),1.0))));
        $actual = $this->sut->getPacksForVehicleId(self::VEHICLE_ID);
        $this->assertEquals("[{\"id\":1,\"title\":\"testName\",\"extrasIncluded\":[],\"prices\":1,\"type\":\"PVP\",\"hasReadmore\":false,\"description\":\"\"}]", json_encode($actual));
    }
}