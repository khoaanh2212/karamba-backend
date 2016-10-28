<?php


use AppBundle\DomainServices\DealerConditionDomainService;
use AppBundle\Entity\DealerCondition;
use AppBundle\Registry\DealerConditionsRegistry;

class DealerConditionDomainServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DealerConditionDomainService
     */
    private $sut;

    /**
     * @var DealerConditionsRegistry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\Registry\DealerConditionsRegistry")->disableOriginalConstructor()->setMethods(
            array("findAll", "findOneById", "delete", "saveOrUpdate")
        )->getMock();
        $this->sut = new DealerConditionDomainService($this->registry);
    }

    public function test_getAllConditions_willCallRegistry_findAll()
    {
        $this->registry->expects($this->once())->method("findAll")->will($this->returnValue(array()));
        $this->sut->getAllConditions();
    }

    public function test_getAllConditions_willReturnDealerConditions()
    {
        $conditions = $this->getDealerConditions();
        $this->registry->expects($this->any())->method("findAll")->will($this->returnValue($conditions));
        $actual = $this->sut->getAllConditions();
        $this->assertEquals($conditions, $actual);
    }

    private function getDealerConditions()
    {
        return array(
            new DealerCondition("test"),
            new DealerCondition("test"),
            new DealerCondition("test")
        );
    }

}