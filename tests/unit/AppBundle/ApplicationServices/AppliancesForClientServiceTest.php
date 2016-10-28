<?php
/**
 * Created by PhpStorm.
 * User: ka
 * Date: 14/10/2016
 * Time: 10:53
 */

use AppBundle\ApplicationServices\AppliancesForClientService;
use AppBundle\DomainServices\AppliancesForClientDomainService;

class AppliancesForClientServiceTest extends PHPUnit_Framework_TestCase
{

    const CLIENT_ID = 'clientId';
    /**
     * @var AppliancesForClientService
     */
    private $sut;

    /**
     * @var AppliancesForClientDomainService
     */
    private $appliancesForClientDomainService;

    protected function setUp()
    {
        $this->appliancesForClientDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AppliancesForClientDomainService")
            ->disableOriginalConstructor()
            ->setMethods(array("findListOfferHaveAtLeastOneMessageFromClientByClientId","findOffersDealersNameFromClientByClientId"))
            ->getMock();
        $this->sut = new AppliancesForClientService($this->appliancesForClientDomainService);
    }

    public function test_FindListOfferHaveAtLeastOneMessageFromClientByClientId_WillCallDomainService_correctData()
    {
        $this->appliancesForClientDomainService->expects($this->once())->method("findListOfferHaveAtLeastOneMessageFromClientByClientId")->with(self::CLIENT_ID);
        $this->executeFindListOfferHaveAtLeastOneMessageFromClientByClientId();
    }

    public function test_FindOffersDealersNameFromClientByClientId_WillCallDomainService_correctData()
    {
        $this->appliancesForClientDomainService->expects($this->once())->method("findOffersDealersNameFromClientByClientId")->with(self::CLIENT_ID);
        $this->executeFindOffersDealersNameFromClientByClientId();
    }

    private function executeFindOffersDealersNameFromClientByClientId()
    {
        $this->sut->findOffersDealersNameFromClientByClientId(self::CLIENT_ID);
    }

    private function executeFindListOfferHaveAtLeastOneMessageFromClientByClientId()
    {
        $this->sut->findListOfferHaveAtLeastOneMessageFromClientByClientId(self::CLIENT_ID);
    }
}
