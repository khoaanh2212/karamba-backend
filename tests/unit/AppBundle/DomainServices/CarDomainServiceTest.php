<?php


use AppBundle\DomainServices\CarDomainService;


class CarDomainServiceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CarDomainService
     */
    private $sut;

    /**
     * @var JatoRegistry
     */
    private $registry;
    

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\JatoRegistry")->disableOriginalConstructor()->setMethods(
            array("getBrands")
        )->getMock();
        $this->sut = new CarDomainService($this->registry);
    }

    public function test_getBrands_callsRegistryWithBrandAndModel()
    {
        $this->registry->expects($this->exactly(1))->method("getBrands");
        $this->sut->getBrands();
    }
}