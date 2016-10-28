<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 17:22
 */

use AppBundle\ApplicationServices\ClientService;
use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\GiftDomainService;
use AppBundle\Entity\CarAppliance;
use AppBundle\Entity\Client;
use AppBundle\Utils\GoogleMapsAccessor;
use AppBundle\Utils\Point;

class ClientServiceTest extends PHPUnit_Framework_TestCase
{
    const ID = "ID";
    const NAME = "client";
    const EMAIL = "email";
    const PASSWORD = "password";
    const ZIPCODE = "SWA15 845";
    const VEHICLE_ID = self::PRICE;
    const BRAND = "brand";
    const MODEL = "model";
    const FUEL_TYPE = "fuelType";
    const TRANSMISSION = "transmission";
    const EXTRAS = "extras";
    const COLOR = "color";
    const PRICE = 1;
    const IMAGE_URL = "image url";
    const DERIVATIVE = "derivative";
    const NUMBER_OF_DOORS = 4;
    const ENGINE_TYPE = "diesel";
    const CITY = "CITY";
    const DEALER_ID = "DEALER_ID";
    /**
     * @var ClientService
     */
    private $sut;

    /**
     * @var ClientDomainService
     */
    private $clientDomainService;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;

    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var GiftDomainService
     */
    private $giftDomainService;

    const DISTANCE_IN_KM = 5;

    /**
     * @var Point
     */
    private $point;

    /**
     * @var Point
     */
    private $dummyPoint;

    protected function setUp()
    {
        $this->point = new Point(1.1,1.2);
        $this->clientDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ClientDomainService")
            ->disableOriginalConstructor()->getMock();
        $this->carApplianceDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\CarApplianceDomainService")->disableOriginalConstructor()->getMock();
        $this->googleMapsAccessor = $this->getMockBuilder("AppBundle\\Utils\\GoogleMapsAccessor")->disableOriginalConstructor()->getMock();
        $this->applianceOfferDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ApplianceOfferDomainService")->disableOriginalConstructor()->getMock();
        $this->dealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerDomainService")->disableOriginalConstructor()->getMock();
        $this->giftDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\GiftDomainService")->disableOriginalConstructor()->getMock();
        $this->dummyPoint = $this->getMockBuilder("AppBundle\\Utils\\Point")->disableOriginalConstructor()->getMock();
        $this->sut = new ClientService($this->clientDomainService, $this->googleMapsAccessor, $this->carApplianceDomainService, $this->applianceOfferDomainService, $this->dealerDomainService, $this->giftDomainService, self::DISTANCE_IN_KM);
    }

    public function test_createClientAndAppliance_shouldCallGoogleMapsAccessorgetPositionFromZipCode_withTheZipCode()
    {
        $this->googleMapsAccessor->expects($this->once())->method("getPositionFromZipCode")->with(self::ZIPCODE)->will($this->returnValue(array("position"=>$this->point, "city"=> self::CITY)));;
        $client = new Client("test name", "test email", "test zip", "city", "test password", $this->point);
        $this->clientDomainService->expects($this->any())->method("create")->will($this->returnValue($client));
        $this->configureCarApplianceAsStub();
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue(array()));
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_createClientAndAppliance_callToDomainServiceCreate()
    {
        $this->googleMapsAccessor->expects($this->any())->method("getPositionFromZipCode")->will($this->returnValue(array("position"=>$this->point, "city"=> self::CITY)));
        $client = new Client("test name", "test email", "test zip", "city", "test password", $this->point);
        $this->clientDomainService->expects($this->once())->method("create")->with(self::NAME, self::EMAIL, self::ZIPCODE, self::CITY, self::PASSWORD)->will($this->returnValue($client));
        $this->configureCarApplianceAsStub();
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue(array()));
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_createClientAndAppliance_shouldCallToCarApplianceDomainServiceWithCorrectData()
    {
        $this->googleMapsAccessor->expects($this->any())->method("getPositionFromZipCode")->will($this->returnValue(array("position"=>$this->point, "city"=> self::CITY)));;
        $client = new Client("test name", "test email", "test zip", "city", "test password", new Point(1.0, 1.0));
        $clientId = $client->getId();
        $this->clientDomainService->expects($this->any())->method("create")->will($this->returnValue($client));
        $this->configureCarApplianceAsStub();
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue(array()));
        $this->carApplianceDomainService->expects($this->once())->method("createAppliance")->with($clientId, self::VEHICLE_ID, self::BRAND, self::MODEL, array(self::EXTRAS));
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_createClientAndApplianceShouldCallToClientPositionGetSquareCoordinatesWithTheDistanceInKms()
    {
        $this->constructClientAsDummy();
        $firstPosition = new Point(self::PRICE, self::PRICE);
        $secondPosition = new Point(2, 2);
        $this->configureCarApplianceAsStub();
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue(array()));
        $this->dummyPoint->expects($this->once())->method("getSquareCoordinates")->with(self::DISTANCE_IN_KM)->will($this->returnValue(array($firstPosition, $secondPosition)));;
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_createClientAndApplianceShouldcallToDealerServiceWithThePositions()
    {
        $this->constructClientAsDummy();
        $firstPosition = new Point(self::PRICE, self::PRICE);
        $secondPosition = new Point(2, 2);
        $this->dummyPoint->expects($this->any())->method("getSquareCoordinates")->will($this->returnValue(array($firstPosition, $secondPosition)));
        $this->dealerDomainService->expects($this->once())->method("findDealerIdsByModelInPosition")->with(self::BRAND, self::MODEL, $firstPosition, $secondPosition)->will($this->returnValue(array()));;
        $this->configureCarApplianceAsStub();
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_createClientAndApplianceShouldCallToApplianceOffersWithTheDealerIds()
    {
        $this->constructClientAsDummy();
        $firstPosition = new Point(self::PRICE, self::PRICE);
        $secondPosition = new Point(2, 2);
        $this->dummyPoint->expects($this->any())->method("getSquareCoordinates")->will($this->returnValue(array($firstPosition, $secondPosition)));
        $dealerIds = array(self::PRICE, 2, 3, self::NUMBER_OF_DOORS);
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue($dealerIds));
        $carAppliance = $this->configureCarApplianceAsStub();
        $this->applianceOfferDomainService->expects($this->once())->method("createOffersForCarAppliance")->with($dealerIds, $carAppliance->getId());
        $this->exerciseCreateClientAndAppliance();
    }

    public function test_getClientById_callServiceWithId()
    {
        $this->clientDomainService->expects($this->once())->method('findById')->with(self::ID)->will($this->returnValue($this->getClient()));
        $this->exerciseGetClientById();
    }

    public function test_updateClient_callToClientDomainService_WithCorrectData()
    {
        $this->clientDomainService->expects($this->once())->method("updateClient")->with(self::ID, self::NAME, self::ZIPCODE, self::PASSWORD);
        $this->exerciseUpdate();
    }

    public function test_updateClient_withZipCode_shouldCallToGoogleMapsAccessor_getPositionFromZipCode_withTheZipCode()
    {
        $this->googleMapsAccessor->expects($this->once())->method("getPositionFromZipCode")->with(self::ZIPCODE);
        $this->exerciseUpdate();
    }

    public function test_getRatingOfDealer_Should_Call_getReviewsByDealer_In_DealerDomainService(){
        $this->dealerDomainService->expects($this->once())->method("getReviewsByDealer")->with(self::DEALER_ID);
        $this->sut->getRatingOfDealer(self::DEALER_ID);
    }

    private function getClient(): Client
    {
        return new Client(self::NAME, self::EMAIL, self::ZIPCODE, self::CITY, self::PASSWORD, new Point(1,1));
    }

    private function exerciseCreateClientAndAppliance()
    {
        $this->sut->createClientAndAppliance(self::NAME, self::EMAIL, self::ZIPCODE, self::PASSWORD, self::VEHICLE_ID, self::BRAND, self::MODEL, array(self::EXTRAS));
    }

    private function exerciseGetClientById()
    {
        return $this->sut->getClientById(self::ID);
    }

    private function exerciseUpdate()
    {
        $this->sut->updateClient(self::ID, self::NAME, self::ZIPCODE, self::PASSWORD);
    }

    private function constructClientAsDummy()
    {
        $this->googleMapsAccessor->expects($this->any())->method("getPositionFromZipCode")->will($this->returnValue(array("position"=>$this->dummyPoint, "city"=>self::CITY)));
        $client = new Client("test name", "test email", "test zip", "test city", "test password", $this->dummyPoint);
        $this->clientDomainService->expects($this->any())->method("create")->will($this->returnValue($client));
    }

    /**
     * @return CarAppliance
     */
    private function configureCarApplianceAsStub()
    {
        $carAppliance = new CarAppliance("clientId", self::VEHICLE_ID, self::BRAND, self::MODEL, self::DERIVATIVE, self::NUMBER_OF_DOORS, self::TRANSMISSION, self::ENGINE_TYPE, self::PRICE, self::IMAGE_URL, array(self::EXTRAS));
        $this->carApplianceDomainService->expects($this->any())->method("createAppliance")->will($this->returnValue($carAppliance));
        return $carAppliance;
    }
}
