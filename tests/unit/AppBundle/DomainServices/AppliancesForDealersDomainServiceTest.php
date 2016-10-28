<?php


use AppBundle\DomainServices\AppliancesForDealerDomainService;
use AppBundle\DTO\AppliancesForDealersDTO;
use AppBundle\Registry\CarApplianceRegistry;

class AppliancesForDealersDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "dealerId";
    /**
     * @var AppliancesForDealerDomainService
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
            ->setMethods(array("findApplianceOffersForDealer","findApplianceOffersArchivedForDealer","findOffersHasAtLeastOneMessageForDealer"))
            ->getMock();
        $this->sut = new AppliancesForDealerDomainService($this->carApplianceRegistry);
    }

    public function test_findApplianceOffersForDealer_willCallRegistry_correctData()
    {
        $this->carApplianceRegistry->expects($this->once())->method("findApplianceOffersForDealer")->with(self::DEALER_ID);
        $this->exerciseFindApplianceOffersByDealer();
    }

    public function test_findApplianceOffersForDealer_willReturnResultFromRegistry()
    {
        $expected = array(
            new AppliancesForDealersDTO("test id", "test client name", "test email", "test brand", "test model", "price", array(), 0, 1, "dealerID", false, "new", "today")
        );
        $this->carApplianceRegistry->expects($this->any())
            ->method("findApplianceOffersForDealer")
            ->will($this->returnValue(
                $expected
            ));
        $actual = $this->exerciseFindApplianceOffersByDealer();
        $this->assertEquals($expected, $actual);
    }

    public function test_findApplianceOffersArchivedForDealer_WillCallRegistry_correctData()
    {
        $this->carApplianceRegistry->expects($this->once())
            ->method("findApplianceOffersArchivedForDealer")
            ->with(self::DEALER_ID);

        $this->sut->findApplianceOffersArchivedForDealer(self::DEALER_ID);
    }

    public function test_findOffersHasAtLeastOneMessageForDealer_WillCallRegistry_correctData()
    {
        $this->carApplianceRegistry->expects($this->once())
            ->method("findOffersHasAtLeastOneMessageForDealer")
            ->with(self::DEALER_ID);

        $this->sut->findOffersHasAtLeastOneMessageForDealer(self::DEALER_ID);
    }

    private function exerciseFindApplianceOffersByDealer()
    {
        return $this->sut->findApplianceOffersForDealer(self::DEALER_ID);
    }
}