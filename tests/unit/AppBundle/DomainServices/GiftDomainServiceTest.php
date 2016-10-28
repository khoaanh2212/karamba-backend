<?php


use AppBundle\DomainServices\GiftDomainService;
use AppBundle\Entity\Gift;
use AppBundle\Registry\GiftRegistry;

class GiftDomainServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GiftDomainService
     */
    private $sut;

    /**
     * @var GiftRegistry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\Registry\GiftRegistry")->disableOriginalConstructor()->setMethods(
            array("findAll", "findOneById", "delete", "saveOrUpdate")
        )->getMock();
        $this->sut = new GiftDomainService($this->registry);
    }

    public function test_getAllGifts_willCallRegistry_findAll()
    {
        $this->registry->expects($this->once())->method("findAll")->will($this->returnValue(array()));
        $this->sut->findGifts();
    }

    public function test_getAllGifts_willReturnGifts()
    {
        $conditions = $this->getAllGifts();
        $this->registry->expects($this->any())->method("findAll")->will($this->returnValue($conditions));
        $actual = $this->sut->findGifts();
        $this->assertEquals($conditions, $actual);
    }

    private function getAllGifts()
    {
        return array(
            new Gift("gift value", "gift name"),
            new Gift("gift value", "gift name"),
            new Gift("gift value", "gift name")
        );
    }

}