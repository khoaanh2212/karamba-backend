<?php

use AppBundle\ApplicationServices\OfferService;
use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\DealerBackgroundImageDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\OfferMailerDomainService;
use AppBundle\DomainServices\OfferMessageDomainService;
use AppBundle\DomainServices\ReviewDomainService;
use AppBundle\DTO\AppliancesForDealersDetailDTO;
use AppBundle\Entity\ApplianceOffer;
use AppBundle\Entity\CarAppliance;
use AppBundle\Entity\Dealer;
use AppBundle\Utils\UUIDGeneratorFactory;
use AppBundle\Utils\GoogleMapsAccessor;
use AppBundle\Utils\Point;
use AppBundle\Entity\Client;

require_once __DIR__ . '/../../utils/TestUUID.php';

class OfferServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "dealerId";
    const CASH_PRIZE = 10.0;
    const FOUND_PRICE = 20.01;
    const IN_STOCK = false;
    const OPORTUNITY_ID = "OPORTUNITY ID";
    const MESSAGE = "message";
    const ID = "ID";
    const OFFER_ID = "offerID";
    const STATE = "state";
    const DATE = "date";
    const CLIENT_NAME = "clientName";
    const CLIENTEMAIL = "clientemail";
    const CITY = "city";
    const BRAND = "brand";
    const MODEL = "model";
    const DERIVATIVE = "derivative";
    const TRANSMISSION = "transmission";
    const MOTOR = "motor";
    const DEALER_NAME = "dealerName";
    const APPLIANCE_ID = "applianceId";
    const DEALER_1 = "dealer1";
    const DEALER_2 = "dealer2";
    const DEALER_3 = "dealer3";

    /**
     * @var OfferService
     */
    private $sut;

    /**
     * @var ApplianceOfferDomainService
     */
    private $offerDomainService;

    /**
     * @var OfferMessageDomainService
     */
    private $offerMessageDomainService;

    /**
     * @var OfferMailerDomainService
     */
    private $offerMailerDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;

    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;

    /**
     * @var ApplianceOffer
     */
    private $applianceOffer;

    /**
     * @var DealerBackgroundImageDomainService
     */
    private $backgroundDomainService;

    protected function setUp()
    {
        $this->backgroundDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerBackgroundImageDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->applianceOffer = $this->getMockBuilder("AppBundle\\Entity\\ApplianceOffer")
            ->disableOriginalConstructor()
            ->getMock();
        $this->carApplianceDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\CarApplianceDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->carApplianceDomainService->expects($this->any())
            ->method("getApplianceById")
            ->will($this->returnValue($this->getCarAppliance()));
        $this->avatarDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AvatarDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->dealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->offerMailerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\OfferMailerDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->offerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ApplianceOfferDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->offerMessageDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\OfferMessageDomainService")
            ->disableOriginalConstructor()
            ->getMock();
        $this->googleMapsAccessor = $this->getMockBuilder("AppBundle\\Utils\\GoogleMapsAccessor")
            ->disableOriginalConstructor()
            ->getMock();
        $this->sut = new OfferService($this->offerDomainService, $this->offerMessageDomainService, $this->offerMailerDomainService, $this->dealerDomainService, $this->avatarDomainService, $this->carApplianceDomainService, $this->backgroundDomainService, $this->googleMapsAccessor);
    }

    public function test_makeAnOffer_Should_Call_ApplianceOfferDomainService_With_CorrectData()
    {
        $this->offerDomainService->expects($this->once())
            ->method("makeAnOffer")
            ->with(self::DEALER_ID, self::OPORTUNITY_ID, self::CASH_PRIZE, self::FOUND_PRICE, self::IN_STOCK)->will($this->returnValue($this->getDTO()));
        $this->exerciseMakerAnOffer();
    }

    public function test_makeAnOffer_Should_Call_OfferMessageAddDealerMessage_With_CorrectData()
    {
        $this->offerDomainService->expects($this->any())
            ->method("makeAnOffer")->will($this->returnValue($this->getDTO()));
        $this->exerciseMakerAnOffer();
    }

    public function test_getOffersForAppliance_Should_Call_ApplianceOfferDomainService_findAllOffersForAppliance()
    {
        $this->offerDomainService->expects($this->once())
            ->method("findAllOffersForAppliance")
            ->with(self::APPLIANCE_ID)
            ->will($this->returnValue($this->getApplianceOffers()));
        $this->sut->getOffersForAppliance(self::APPLIANCE_ID, $this->getClient());
    }

    public function test_getOffersForAppliance_Should_Call_DealerRegistry_FindByIds_WithThe_DealersIds()
    {
        $this->offerDomainService->expects($this->any())
            ->method("findAllOffersForAppliance")
            ->will($this->returnValue($this->getApplianceOffers()));
        $this->dealerDomainService->expects($this->once())
            ->method("findByIds")
            ->with(array(
                self::DEALER_1,
                self::DEALER_2,
                self::DEALER_3,
            ));
        $this->sut->getOffersForAppliance(self::APPLIANCE_ID, $this->getClient());
    }


    public function test_getOfferDetailCalledWithOfferDetailShouldCallApplianceOfferDomainServiceFindApplianceOfferById()
    {
        $this->markTestSkipped();
        $this->offerDomainService->expects($this->once())
                ->method('findApplianceOfferById')
                ->with(self::OFFER_ID)->will($this->returnValue($this->applianceOffer));
        $this->applianceOffer->expects($this->any())->method("getApplianceId")->will($this->returnValue(self::APPLIANCE_ID));
        $this->applianceOffer->expects($this->any())->method("getDealerId")->will($this->returnValue(self::DEALER_1));
        $this->sut->getOfferDetailForOffer(self::OFFER_ID);
    }

    public function test_getOfferDetailCalledWithOfferDetail_shouldCallCarApplianceGetApplianceById()
    {
        $this->markTestSkipped();
        $this->offerDomainService->expects($this->any())
            ->method('findApplianceOfferById')
            ->will($this->returnValue($this->applianceOffer));
        $this->applianceOffer->expects($this->any())->method("getApplianceId")->will($this->returnValue(self::APPLIANCE_ID));
        $this->applianceOffer->expects($this->any())->method("getDealerId")->will($this->returnValue(self::DEALER_1));
        $this->carApplianceDomainService->expects($this->once())->method("getApplianceById")->with(self::APPLIANCE_ID);
        $this->sut->getOfferDetailForOffer(self::OFFER_ID);
    }

    public function test_getOfferDetailCAlledWithOfferDetail_shouldCallgetDealerByIdWithTheId()
    {
        $this->markTestSkipped();
        $this->offerDomainService->expects($this->any())
            ->method('findApplianceOfferById')
            ->will($this->returnValue($this->applianceOffer));
        $this->applianceOffer->expects($this->any())->method("getApplianceId")->will($this->returnValue(self::APPLIANCE_ID));
        $this->applianceOffer->expects($this->any())->method("getDealerId")->will($this->returnValue(self::DEALER_1));
        $this->dealerDomainService->expects($this->once())->method("getDealerById")->with(self::DEALER_1);
        $this->sut->getOfferDetailForOffer(self::OFFER_ID);
    }

    public function test_getOfferDetailShouldCallAvatarDomainServiceWithTheDealerId()
    {
        $this->markTestSkipped();
        $this->offerDomainService->expects($this->any())
            ->method('findApplianceOfferById')
            ->will($this->returnValue($this->applianceOffer));
        $this->applianceOffer->expects($this->any())->method("getApplianceId")->will($this->returnValue(self::APPLIANCE_ID));
        $this->applianceOffer->expects($this->any())->method("getDealerId")->will($this->returnValue(self::DEALER_1));
        $this->avatarDomainService->expects($this->once())->method("getAvatarByDealerId")->with(self::DEALER_1);
        $this->sut->getOfferDetailForOffer(self::OFFER_ID);
    }

    public function test_getOffersForAppliance_Should_Call_AvatarDomainService_FindAllByDealerIds()
    {
        $this->offerDomainService->expects($this->any())
            ->method("findAllOffersForAppliance")
            ->will($this->returnValue($this->getApplianceOffers()));
        $this->avatarDomainService->expects($this->once())
            ->method("findAllByDealerIds")
            ->with(array(
                self::DEALER_1,
                self::DEALER_2,
                self::DEALER_3,
            ));
        $this->sut->getOffersForAppliance(self::APPLIANCE_ID, $this->getClient());
    }

    public function test_getOffersForAppliance_Should_Return_CorrectData()
    {
        $this->offerDomainService->expects($this->any())
            ->method("findAllOffersForAppliance")
            ->will($this->returnValue($this->getApplianceOffers()));
        $this->dealerDomainService->expects($this->any())
            ->method("findByIds")
            ->will($this->returnValue(array(
                $this->constructDealer(self::DEALER_1),
                $this->constructDealer(self::DEALER_2),
                $this->constructDealer(self::DEALER_3),
            )));
        $this->avatarDomainService->expects($this->any())
            ->method("findAllByDealerIds")
            ->will($this->returnValue(
                array(
                    new \AppBundle\Entity\Avatar(self::DEALER_1),
                    new \AppBundle\Entity\Avatar(self::DEALER_3)
                )
            ));
        $actual = $this->sut->getOffersForAppliance(self::APPLIANCE_ID, $this->getClient());
        $created = $actual["offers"][0]->created;
        $this->assertEquals("[{\"id\":\"TEST_ID\",\"dealerId\":\"dealer1\",\"applianceId\":\"applianceId\",\"state\":\"new_opportunity\",\"cashPrice\":null,\"foundedPrice\":null,\"inStock\":null,\"isRead\":false,\"dealerInfo\":{\"name\":\"test\",\"address\":null,\"zipCode\":null,\"vendorName\":\"test\",\"vendorRole\":\"test\",\"email\":\"test\",\"schedule\":null,\"deliveryConditions\":null,\"specialConditions\":null,\"phoneNumber\":null,\"firstUse\":true,\"description\":null,\"generalConditions\":[],\"avatar\":{\"url\":\"\/images\/avatars\/\",\"label\":null},\"background\":null,\"longitude\":null,\"latitude\":null},\"isBestPrice\":false,\"isClosest\":true,\"isHighestRating\":null,\"distance\":-1,\"created\":\"$created\",\"ratings\":null},{\"id\":\"TEST_ID\",\"dealerId\":\"dealer2\",\"applianceId\":\"applianceId\",\"state\":\"new_opportunity\",\"cashPrice\":null,\"foundedPrice\":null,\"inStock\":null,\"isRead\":false,\"dealerInfo\":{\"name\":\"test\",\"address\":null,\"zipCode\":null,\"vendorName\":\"test\",\"vendorRole\":\"test\",\"email\":\"test\",\"schedule\":null,\"deliveryConditions\":null,\"specialConditions\":null,\"phoneNumber\":null,\"firstUse\":true,\"description\":null,\"generalConditions\":[],\"avatar\":null,\"background\":null,\"longitude\":null,\"latitude\":null},\"isBestPrice\":false,\"isClosest\":true,\"isHighestRating\":null,\"distance\":-1,\"created\":\"$created\",\"ratings\":null},{\"id\":\"TEST_ID\",\"dealerId\":\"dealer3\",\"applianceId\":\"applianceId\",\"state\":\"new_opportunity\",\"cashPrice\":null,\"foundedPrice\":null,\"inStock\":null,\"isRead\":false,\"dealerInfo\":{\"name\":\"test\",\"address\":null,\"zipCode\":null,\"vendorName\":\"test\",\"vendorRole\":\"test\",\"email\":\"test\",\"schedule\":null,\"deliveryConditions\":null,\"specialConditions\":null,\"phoneNumber\":null,\"firstUse\":true,\"description\":null,\"generalConditions\":[],\"avatar\":{\"url\":\"\/images\/avatars\/\",\"label\":null},\"background\":null,\"longitude\":null,\"latitude\":null},\"isBestPrice\":false,\"isClosest\":true,\"isHighestRating\":null,\"distance\":-1,\"created\":\"$created\",\"ratings\":null}]", json_encode($actual["offers"]));
    }

    public function test_getApplianceOffers_Should_Return_CorrectData()
    {
        $actual = $this->sut->getOfferBestPrice($this->getApplianceOffers());
        $this->assertEquals($actual, array());
    }

    public function test_getOfferDealerNearest_Should_Return_CorrectData()
    {
        $actual = $this->sut->getOfferDealerNearest(array(
            $this->constructDealer(self::DEALER_1),
            $this->constructDealer(self::DEALER_2),
            $this->constructDealer(self::DEALER_3),
        ), $this->getClient());
        $this->assertEquals($actual, array('nearestDealerIds' => Array(
            0 => 'dealer1',
            1 => 'dealer2',
            2 => 'dealer3'
        ), 'distances' => Array(
            'dealer1' => -1,
            'dealer2' => -1,
            'dealer3' => -1
        )
        ));
    }


    private function constructDealer($id)
    {
        UUIDGeneratorFactory::setInstance(new TestUUID($id));
        return new Dealer("test", "test", "test", "test", "test", "test");
    }

    private function getDTO()
    {
        return new AppliancesForDealersDetailDTO(self::ID, self::OFFER_ID, self::STATE, self::DATE, self::CLIENT_NAME, self::CLIENTEMAIL, self::CITY, 12, 12, self::BRAND, self::MODEL, self::DERIVATIVE, self::TRANSMISSION, self::MOTOR, 4, 10, array(), 0, 112, "dealerId", self::DEALER_NAME);
    }

    private function exerciseMakerAnOffer()
    {
        $this->sut->makeAnOffer(self::DEALER_ID, self::OPORTUNITY_ID, self::CASH_PRIZE, self::FOUND_PRICE, self::IN_STOCK, self::MESSAGE);
    }

    private function getApplianceOffers()
    {
        return array(
            new ApplianceOffer(self::DEALER_1, self::APPLIANCE_ID),
            new ApplianceOffer(self::DEALER_2, self::APPLIANCE_ID),
            new ApplianceOffer(self::DEALER_3, self::APPLIANCE_ID),
        );
    }

    /**
     * @return CarAppliance
     */
    protected function getCarAppliance()
    {
        return new CarAppliance("client", 2, "brand", "model", "derivative", 1, "A", "motor", 11, "test");
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return new Client('client', 'client@test.com', '08001', 'city', '123456', new Point('2.2222', '3.3333'));
    }
}