<?php
use AppBundle\ApplicationServices\CarApplianceService;
use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\AttachmentDomainService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\OfferMessageDomainService;
use AppBundle\Entity\CarAppliance;
use AppBundle\Entity\Client;
use AppBundle\Utils\Point;

/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 26/08/16
 * Time: 18:46
 */
class CarApplianceServiceTest extends PHPUnit_Framework_TestCase
{
    const CLIENT_ID = "client id";
    const VEHICLE_ID = 1;
    const BRAND = "brand";
    const MODEL = "model";
    const FUEL = "fuel";
    const TRANSMISSION = "manual";
    const DOORS = "5";
    const EXTRAS = "extras";
    const COLOR = "color";
    const PRICE = 12.0;
    const IMAGE_URL = "imageUrl";
    const DERIVATIVE = "derivative";
    const NUMBER_OF_DOORS = 4;
    const ENGINE_TYPE = "diesel";
    /**
     * @var CarApplianceService
     */
    private $sut;

    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var ClientDomainService
     */
    private $clientDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;
    
    /**
     * @var OfferMessageDomainService
     */
    private $offerMessageDomainService;
    
    /**
     * @var Point
     */
    private $point;

    private $squarePoint1;

    private $squarePoint2;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;

    /**
     * @var AttachmentDomainService
     */
    private $attachmentDomainService;

    const DISTANCE_IN_KM = 15;

    protected function setUp()
    {
        $this->avatarDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AvatarDomainService")->disableOriginalConstructor()->getMock();
        $this->carApplianceDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\CarApplianceDomainService")->disableOriginalConstructor()->getMock();
        $this->clientDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ClientDomainService")->disableOriginalConstructor()->getMock();
        $this->dealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerDomainService")->disableOriginalConstructor()->getMock();
        $this->applianceOfferDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ApplianceOfferDomainService")->disableOriginalConstructor()->getMock();
        $this->offerMessageDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\OfferMessageDomainService")->disableOriginalConstructor()->getMock();
        $this->attachmentDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AttachmentDomainService")->disableOriginalConstructor()->getMock();
        $this->point = $this->getMockBuilder("AppBundle\\Utils\\Point")->disableOriginalConstructor()->getMock();
        $this->squarePoint1 = new Point();
        $this->squarePoint2 = new Point();
        $this->sut = new CarApplianceService($this->carApplianceDomainService, $this->clientDomainService, $this->dealerDomainService, $this->applianceOfferDomainService, $this->offerMessageDomainService, $this->avatarDomainService , $this->attachmentDomainService, self::DISTANCE_IN_KM);
    }

    public function test_createAppliance_willCallDomainServiceCreateApplianceWithCorrectData()
    {
        $this->configureClientDomainServiceAsStub();
        $this->configurePositionAsStub();
        $this->configureDomainServiceAsStub();
        $this->carApplianceDomainService->expects($this->once())->method("createAppliance")->with(self::CLIENT_ID, self::VEHICLE_ID, self::BRAND, self::MODEL, array(self::EXTRAS))
            ->will($this->returnValue($this->getCarAppliance()));
        $this->exerciseCreateAppliance();
    }

    public function test_createAppliance_willCallToClientDomainServiceFindByIdWithTheClientId()
    {
        $client = new Client("name", "email", "zipCode", "city", "password", $this->point);
        $this->configurePositionAsStub();
        $this->configureCarApplianceAsStub();
        $this->configureDomainServiceAsStub();
        $this->clientDomainService->expects($this->once())->method("findById")->with(self::CLIENT_ID)->will($this->returnValue($client));
        $this->exerciseCreateAppliance();
    }

    public function test_createAppliance_willCallToClientPositionGetSquareCoordinatesWithTheDistance()
    {
        $this->configureClientDomainServiceAsStub();
        $this->configureDomainServiceAsStub();
        $this->configureCarApplianceAsStub();
        $this->point->expects($this->once())->method("getSquareCoordinates")->with(self::DISTANCE_IN_KM)->will($this->returnValue(array($this->squarePoint1, $this->squarePoint2)));;
        $this->exerciseCreateAppliance();
    }

    public function test_createAppliance_willCallToDealerServicefindDealerIdsByModelInPosition()
    {
        $this->configureClientDomainServiceAsStub();
        $this->configureDomainServiceAsStub();
        $this->configureCarApplianceAsStub();
        $this->configurePositionAsStub();
        $this->dealerDomainService->expects($this->once())->method("findDealerIdsByModelInPosition")->with(self::BRAND, self::MODEL, $this->squarePoint1, $this->squarePoint2);
        $this->exerciseCreateAppliance();
    }

    public function test_createAppliance_whenDistanceInKmIsZero_willCallToDealerDomainServiceFindDEalerIdsByModel()
    {
        $this->configureClientDomainServiceAsStub();
        $this->configureDomainServiceAsStub();
        $this->configureCarApplianceAsStub();
        $this->configurePositionAsStub();
        $this->dealerDomainService->expects($this->once())->method("findDealerIdsByModel")->with(self::BRAND, self::MODEL)->will($this->returnValue(array()));
        $this->sut->setDistanceInKm(0);
        $this->exerciseCreateAppliance();
    }

    public function test_createAppliance_willCallToApplianceOfferDomainServicecreateOffersForCarAppliance_correctData()
    {
        $this->configureClientDomainServiceAsStub();
        $this->configurePositionAsStub();
        $dealerIds = $this->configureDomainServiceAsStub();
        $carAppliance = $this->configureCarApplianceAsStub();
        $this->applianceOfferDomainService->expects($this->once())->method("createOffersForCarAppliance")->with($dealerIds, $carAppliance->getId());
        $this->exerciseCreateAppliance();
    }

    private function exerciseCreateAppliance()
    {
        $this->sut->createAppliance(self::CLIENT_ID, self::VEHICLE_ID, self::BRAND, self::MODEL, array(self::EXTRAS));
    }

    private function configureClientDomainServiceAsStub()
    {
        $client = new Client("name", "email", "zipCode", "city", "password", $this->point);
        $this->clientDomainService->expects($this->any())->method("findById")->will($this->returnValue($client));
    }

    private function configurePositionAsStub()
    {
        $this->point->expects($this->any())->method("getSquareCoordinates")->will($this->returnValue(array($this->squarePoint1, $this->squarePoint2)));
    }

    /**
     * @return array
     */
    private function configureDomainServiceAsStub()
    {
        $dealerIds = array(1, 2, 3, 4);
        $this->dealerDomainService->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue($dealerIds));
        return $dealerIds;
    }

    /**
     * @return CarAppliance
     */
    private function configureCarApplianceAsStub()
    {
        $carAppliance = $this->getCarAppliance();
        $this->carApplianceDomainService->expects($this->any())->method("createAppliance")->will($this->returnValue($carAppliance));
        return $carAppliance;
    }

    /**
     * @return CarAppliance
     */
    private function getCarAppliance()
    {
        return new CarAppliance(self::CLIENT_ID, self::VEHICLE_ID, self::BRAND, self::MODEL, self::DERIVATIVE, self::NUMBER_OF_DOORS, self::TRANSMISSION, self::ENGINE_TYPE, self::PRICE, self::IMAGE_URL, array(self::EXTRAS));
    }


}