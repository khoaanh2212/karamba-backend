<?php
/**
 * Created by PhpStorm.
 * User: ka
 * Date: 14/10/2016
 * Time: 10:24
 */
use AppBundle\DomainServices\AppliancesForClientDomainService;
use AppBundle\Registry\CarApplianceRegistry;

class AppliancesForClientDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const CLIENT_ID = "clientId";
    /**
     * @var AppliancesForClientDomainService
     */
    private $sut;

    /**
     * @var CarApplianceRegistry
     */
    private $carApplianceRegistry;

    protected function setUp()
    {
        $this->carApplianceRegistry = $this->getMockBuilder("AppBundle\\Registry\\CarApplianceRegistry")
            ->disableOriginalConstructor()
            ->setMethods(array("findListOfferHaveAtLeastOneMessageFromClientByClientId"))
            ->getMock();
        $this->sut = new AppliancesForClientDomainService($this->carApplianceRegistry);
    }

    public function test_findListOfferHaveAtLeastOneMessageFromClientByClientId_willCallRegistry_correctData()
    {
        $this->carApplianceRegistry->expects($this->once())->method("findListOfferHaveAtLeastOneMessageFromClientByClientId")->with(self::CLIENT_ID);
        $this->executeFindListOfferHaveAtLeastOneMessageFromClientByClientId();
    }

    private function executeFindListOfferHaveAtLeastOneMessageFromClientByClientId()
    {
        return $this->sut->findListOfferHaveAtLeastOneMessageFromClientByClientId(self::CLIENT_ID);
    }
}