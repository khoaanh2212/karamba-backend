<?php

use AppBundle\ApplicationServices\AppliancesForDealerService;
use AppBundle\DomainServices\AppliancesForDealerDomainService;
use AppBundle\DomainServices\StockCarsDomainService;
use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DTO\AppliancesForDealersDetailDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\Entity\Price;
use AppBundle\Entity\StockCar;

class AppliancesForDealerServiceTest extends PHPUnit_Framework_TestCase
{
    const ID = "test id";
    const OFFER_ID = "offer id";
    const DEALER_ID = "dealer id";
    const DEALER_NAME = "dealer name";
    const APPLIANCE_OFFER_ID = "test id";
    const TESTBRAND = "testbrand";
    const TESTMODEL = "testmodel";
    /**
     * @var AppliancesForDealerService
     */
    private $sut;

    /**
     * @var AppliancesForDealerDomainService
     */
    private $appliancesForDealerDomainService;

    /**
     * @var StockCarsDomainService
     */
    private $stockCarsDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;

    protected function setUp()
    {
        $this->appliancesForDealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AppliancesForDealerDomainService")->disableOriginalConstructor()->getMock();
        $this->stockCarsDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\StockCarsDomainService")->disableOriginalConstructor()->getMock();
        $this->applianceOfferDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ApplianceOfferDomainService")->disableOriginalConstructor()->getMock();
        $this->sut = new AppliancesForDealerService($this->appliancesForDealerDomainService, $this->stockCarsDomainService, $this->applianceOfferDomainService);
    }

    public function test_findApplianceOffersForDealer_callToDomainServicefindApplianceOffersForDealer()
    {
        $this->appliancesForDealerDomainService->expects($this->once())->method("findApplianceOffersForDealer")->with(self::DEALER_ID);
        $this->sut->findApplianceOffersForDealer(self::DEALER_ID);
    }

    public function test_getApplianceDetail_withTheDetailIdShouldCallDomainServicegetAppliance()
    {
        $applianceDetailDto = $this->constructApplianceDetailDto();
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("getAppliance")
            ->with(self::APPLIANCE_OFFER_ID)
            ->will($this->returnValue($applianceDetailDto));
        $this->exerciseGetDetail();
    }

    public function test_getApplianceDetail_withTheDetailIdShouldCallStockCarretrieveStockCarsByDealer()
    {
        $applianceDetailDto = $this->constructApplianceDetailDto();
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("getAppliance")
            ->with(self::APPLIANCE_OFFER_ID)
            ->will($this->returnValue($applianceDetailDto));
        $this->stockCarsDomainService->expects($this->once())
            ->method("retrieveStockCarsByDealer")
            ->with(self::DEALER_ID);
        $this->exerciseGetDetail();
    }

    public function test_getApplianceDetail_withTheDetailIdShouldCallApplianceOfferMarkAsRead()
    {
        $applianceDetailDto = $this->constructApplianceDetailDto();
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("getAppliance")
            ->will($this->returnValue($applianceDetailDto));
        $this->applianceOfferDomainService->expects($this->once())
            ->method("markAsRead")
            ->with($applianceDetailDto->offerId);
        $this->exerciseGetDetail();
    }

    public function test_getApplianceDetail_willReturnCorrectData()
    {
        $this->markTestSkipped();
        $applianceDetailDto = $this->constructApplianceDetailDto();
        $this->stockCarsDomainService->expects($this->any())
            ->method("retrieveStockCarsByDealer")
            ->will($this->returnValue(array(
                $this->constructStockCar(self::TESTBRAND, self::TESTMODEL),
                $this->constructStockCar("anotherbrand", "anothermodel"),
            )));
        $this->appliancesForDealerDomainService->expects($this->any())
            ->method("getAppliance")
            ->will($this->returnValue($applianceDetailDto));
        $this->applianceOfferDomainService->expects($this->once())
            ->method("markAsRead")
            ->with($applianceDetailDto->offerId);
        $actual = $this->exerciseGetDetail();
        $this->assertEquals("{\"similar\":true,\"appliance\":{\"id\":\"testid\",\"clientName\":\"testclientname\",\"clientEmail\":\"testclientemail\",\"brand\":\"testbrand\",\"model\":\"testmodel\",\"extras\":[],\"numberOffers\":0,\"vehicleId\":1,\"dealerId\":\"dealerid\",\"pvp\":\"12.0\",\"package\":null,\"color\":null,\"state\":\"new\",\"isNew\":\"new\"}}", json_encode($actual));
    }

    public function test_getApplianceOffersArchivedForDealer_WillCallDomainService_correctData()
    {
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("findApplianceOffersArchivedForDealer")
            ->with(self::DEALER_ID);
        $this->sut->getApplianceOffersArchivedForDealer(self::DEALER_ID);
    }

    public function test_getOffersHasConversationsForDealer_WillCallDomainService_correctData(){
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("findApplianceOffersForDealer")
            ->with(self::DEALER_ID);
        $this->appliancesForDealerDomainService->expects($this->once())
            ->method("findOffersHasAtLeastOneMessageForDealer")
            ->with(self::DEALER_ID);
        $this->sut->getOffersHasConversationsForDealer(self::DEALER_ID);
    }

    private function exerciseGetDetail()
    {
        return $this->sut->getApplianceDetail(self::DEALER_ID, self::APPLIANCE_OFFER_ID);
    }

    /**
     * @return StockCar
     */
    private function constructStockCar(string $brand, string $model)
    {
        return new StockCar(self::DEALER_ID, new VehicleDTO(1, $brand, $brand, $model, $model, "modelyearToDisplay", "fuelType", "fuelTypeToDisplay", "derivative", "transmission", "numberOfDoors", "derivative to display", "price", "priceDisplay"), new VehicleOptionDTO(1, "testoption", "tstoptiontypename", 1.0, "display price"), array(), new Price(1, 1, 1), "url");
    }

    private function constructApplianceDetailDto()
    {
        return new AppliancesForDealersDetailDTO(self::ID, self::OFFER_ID, "teststate", "date", "clientname", "clientemail", "city", 12, 12, self::TESTBRAND, self::TESTMODEL, "derivative", "tranmission", "motor", 4, 10, array(), 0, 112, self::DEALER_ID, self::DEALER_NAME);
    }
}