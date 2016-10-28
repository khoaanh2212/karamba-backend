<?php

use AppBundle\ApplicationServices\DealerService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\DealerApplicationDomainService;
use AppBundle\DomainServices\DealerBackgroundImageDomainService;
use AppBundle\DomainServices\DealerConditionDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\StockCarsDomainService;
use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\DealerWithConditionsDTO;
use AppBundle\Entity\AvailableCars;
use AppBundle\Entity\Avatar;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\DealerBackgroundImage;
use AppBundle\Entity\StockCar;
use AppBundle\Utils\GoogleMapsAccessor;
use AppBundle\Utils\Point;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DealerServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_NAME = "dealer name";
    const ADDRESS = "address";
    const VENDOR_NAME = "vendor name";
    const VENDOR_ROLE = "vendor role";
    const EMAIL = "email";
    const PASSWORD = "password";
    const SCHEDULING = "scheduling";
    const DELIVERY_CONDITIONS = "delivery conditions";
    const SPECIAL_CONDITIONS = "special conditions";
    const PHONE = "phone";
    const GENERAL_CONDITIONS = array(1);
    const TOKEN = "token";
    const ID = "ID";
    const HOW = "HOW";
    const DESCRIPTION = "description";
    const STOCKCAR_JSON = "{\"brand\":{\"name\":\"Citroën\"},\"model\":{\"brand\":\"Citroën\",\"name\":\"C4\",\"year\":\"2015\"},\"engine\":{\"vehicleId\":34748920160701,\"makeKey\":\"Citroën\",\"makeNameToDisplay\":\"Citroën\",\"modelKey\":\"C4\",\"modelNameToDisplay\":\"C4\",\"modelYearToDisplay\":\"2015\",\"fuelType\":\"D\",\"fuelTypeToDisplay\":\"Diesel\",\"derivative\":\"C4 BlueHDi 100 Feel\",\"transmission\":\"M\",\"numberOfDoorsToDisplay\":\"5\",\"derivativeToDisplay\":\"C4 BlueHDi 100 Feel\",\"price\":\"22360\",\"priceToDisplay\":\"€22,360\"},\"color\":{\"optionId\":1529,\"optionName\":\"Blanco Banquise (opaco)\",\"optionTypeName\":\"Colour\",\"price\":0,\"displayPrice\":\"€0\"},\"extras\":[{\"optionId\":1432,\"optionName\":\"Tejido C&T Hemitage gris oscuro\",\"optionTypeName\":\"Colour\",\"price\":0,\"displayPrice\":\"€0\"}],\"pvp\":22920,\"cash\":18336,\"discount\":20,\"photoUrl\":\"https://sslphotos.jato.com/PHOTO300/SSCE/CITROEN/C4/2015/5HA.JPG\"}";
    const STOCKCAR_FORUPDATE_JSON = "{\"id\":\"test\",\"brand\":{\"name\":\"Citroën\"},\"model\":{\"brand\":\"Citroën\",\"name\":\"C4\",\"year\":\"2015\"},\"engine\":{\"vehicleId\":34748920160701,\"makeKey\":\"Citroën\",\"makeNameToDisplay\":\"Citroën\",\"modelKey\":\"C4\",\"modelNameToDisplay\":\"C4\",\"modelYearToDisplay\":\"2015\",\"fuelType\":\"D\",\"fuelTypeToDisplay\":\"Diesel\",\"derivative\":\"C4 BlueHDi 100 Feel\",\"transmission\":\"M\",\"numberOfDoorsToDisplay\":\"5\",\"derivativeToDisplay\":\"C4 BlueHDi 100 Feel\",\"price\":\"22360\",\"priceToDisplay\":\"€22,360\"},\"color\":{\"optionId\":1529,\"optionName\":\"Blanco Banquise (opaco)\",\"optionTypeName\":\"Colour\",\"price\":0,\"displayPrice\":\"€0\"},\"extras\":[{\"optionId\":1432,\"optionName\":\"Tejido C&T Hemitage gris oscuro\",\"optionTypeName\":\"Colour\",\"price\":0,\"displayPrice\":\"€0\"}],\"pvp\":22920,\"cash\":18336,\"discount\":20,\"photoUrl\":\"https://sslphotos.jato.com/PHOTO300/SSCE/CITROEN/C4/2015/5HA.JPG\"}";
    const ZIP_CODE = "zipCode";
    /**
     * @var DealerService
     */
    private $sut;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var DealerConditionDomainService
     */
    private $dealerConditionService;

    /**
     * @var DealerApplicationDomainService
     */
    private $dealerApplicationDomainService;

    /**
     * @var UploadedFile
     */
    private $avatarFile;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;

    /**
     * @var DealerBackgroundImageDomainService
     */
    private $dealerBackgroundDomainService;

    /**
     * @var StockCarsDomainService
     */
    private $stockCarsDomainService;

    /**
     * @var UploadedFile
     */
    private $backgroundFile;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;


    protected function setUp()
    {
        $this->avatarFile = $this->getMockBuilder("Symfony\\Component\\HttpFoundation\\File\\UploadedFile")->disableOriginalConstructor()->getMock();
        $this->backgroundFile = $this->getMockBuilder("Symfony\\Component\\HttpFoundation\\File\\UploadedFile")->disableOriginalConstructor()->getMock();
        $this->dealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerDomainService")->disableOriginalConstructor()->getMock();
        $this->dealerApplicationDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerApplicationDomainService")->disableOriginalConstructor()->getMock();
        $this->dealerApplicationDomainService->expects($this->any())->method("retrieveApplicationAndValidate")->will($this->returnValue($this->getDataFromAcceptedApplication()));
        $this->dealerConditionService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerConditionDomainService")->disableOriginalConstructor()->getMock();
        $this->avatarDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AvatarDomainService")->disableOriginalConstructor()->getMock();
        $this->dealerBackgroundDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerBackgroundImageDomainService")->disableOriginalConstructor()->getMock();
        $this->availableCarsDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\AvailableCarsDomainService")->disableOriginalConstructor()->getMock();
        $this->carDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\CarDomainService")->disableOriginalConstructor()->getMock();
        $this->stockCarsDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\StockCarsDomainService")->disableOriginalConstructor()->getMock();
        $this->googleMapsAccessor = $this->getMockBuilder("AppBundle\\Utils\\GoogleMapsAccessor")->disableOriginalConstructor()->getMock();
        $this->sut = new DealerService($this->dealerDomainService, $this->dealerApplicationDomainService, $this->dealerConditionService, $this->avatarDomainService, $this->dealerBackgroundDomainService, $this->availableCarsDomainService, $this->carDomainService, $this->stockCarsDomainService, $this->googleMapsAccessor);
    }

    public function test_createDealer_callToDomainServiceCreateDealer()
    {
        $this->dealerDomainService->expects($this->once())->method("createDealer")->with(self::DEALER_NAME, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::EMAIL, self::PASSWORD);
        $this->exerciseCreateDealer();
    }

    public function test_createDealer_callToRetrieveApplicationAndValidate()
    {
        $this->dealerApplicationDomainService->expects($this->once())->method("retrieveApplicationAndValidate")->with(self::TOKEN);
        $this->exerciseCreateDealer();
    }
    public function test_createDealer_callToProcessApplication()
    {
        $this->dealerApplicationDomainService->expects($this->once())->method("processApplication");
        $this->exerciseCreateDealer();
    }

    public function test_getDealerById_callServiceWithId()
    {
        $this->dealerDomainService->expects($this->once())->method('getDealerById')->with(self::ID)->will($this->returnValue($this->getDealer()));
        $this->exerciseGetDealerById();
    }

    public function test_updateDealer_withZipCode_shouldCallToGoogleMapsAccessor_getPositionFromZipCode_withTheZipCode()
    {
        $this->googleMapsAccessor->expects($this->once())->method("getPositionFromZipCode")->with(self::ZIP_CODE);
        $this->exerciseUpdate();
    }

    public function test_updateDealer_callToDealerDomainService_withCorrectData()
    {
        $point = new Point();
        $this->googleMapsAccessor->expects($this->any())->method("getPositionFromZipCode")->will($this->returnValue(array("position"=>$point)));
        $this->dealerDomainService->expects($this->once())->method("updateDealer")->with(self::ID, self::DEALER_NAME, self::DESCRIPTION, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::PASSWORD, self::ADDRESS, self::SCHEDULING, self::DELIVERY_CONDITIONS, self::SPECIAL_CONDITIONS, self::GENERAL_CONDITIONS, self::ZIP_CODE, $point);
        $this->exerciseUpdate();
    }

    public function test_updateDealer_callToAvatarDomainServiceCreateAvatarFromUploadFileWithCorrectData()
    {
        $this->avatarDomainService->expects($this->once())->method('createAvatarFromUploadFile')->with($this->avatarFile, self::ID);
        $this->exerciseUpdate();
    }

    public function test_updateDealer_callToDealerBackgroundDomainServiceWithCorrectData()
    {
        $this->dealerBackgroundDomainService->expects($this->once())->method('createBackgroundImageFromUploadFile')->with($this->backgroundFile, self::ID);
        $this->exerciseUpdate();
    }

    public function test_deleteStockCar_shouldCallDomainServiceWithCorrectId()
    {
        $this->stockCarsDomainService->expects($this->once())->method('delete')->with(self::ID);
        $this->sut->deleteStockCar(self::ID);
    }

    public function test_buildEntityFromJSON_buildsStockCar_fromJSON(){
        $json = json_decode(self::STOCKCAR_JSON);
        $entity = $this->sut->_buildEntityFromJSON("test", $json);
        $this->assertTrue($entity instanceof StockCar);
    }
    
    public function test_updateStockCar_willCallDomainServiceToUpdate(){
        $json = json_decode(self::STOCKCAR_FORUPDATE_JSON);
        $this->stockCarsDomainService->expects($this->once())->method('addStockCar');
        $this->sut->updateStockCar("test", $json);
    }

    public function test_getDealerById_returnCorrectDealer()
    {
        $dealer = $this->getDealer();
        $dealerDTO = $dealer->toDTO();
        $this->configureRegistryAsStub();
        $actual = $this->exerciseGetDealerById();
        $dealerDTO->password = "password";
        $actual->profile->password = "password";
        $expected = new DealerWithConditionsDTO($dealerDTO, array());
        $this->assertEquals($expected, $actual);
    }

    public function test_getDealerById_shouldCallAvatarDomainServicegetAvatarByDealerId()
    {
        $this->configureRegistryAsStub();
        $this->avatarDomainService->expects($this->once())->method("getAvatarByDealerId")->with(self::ID);
        $this->exerciseGetDealerById();
    }

    public function test_getDealerById_shouldCallBackgroundImagegetBackgroundImageByDealerId()
    {
        $this->configureRegistryAsStub();
        $this->dealerBackgroundDomainService->expects($this->once())->method("getBackgroundImageByDealerId")->with(self::ID);
        $this->exerciseGetDealerById();
    }

    public function test_getDealerById_withAvatarAndBackgroundImageShouldReturnCorrectData()
    {
        $avatar = new Avatar(self::ID);
        $avatar->setImageName("avatar_image");
        $background = new DealerBackgroundImage(self::ID);
        $background->setImageName("background_image");
        $this->avatarDomainService->expects($this->any())->method("getAvatarByDealerId")->will($this->returnValue($avatar));
        $this->dealerBackgroundDomainService->expects($this->any())->method("getBackgroundImageByDealerId")->will($this->returnValue($background));
        $dealer = $this->getDealer();
        $dealerDTO = $dealer->toDTO();
        $this->configureRegistryAsStub();
        $actual = $this->exerciseGetDealerById();
        $dealerDTO->password = "password";
        $actual->profile->password = "password";
        $expected = new DealerWithConditionsDTO($dealerDTO, array());
        $expected->addAvatar($avatar->toDTO());
        $expected->addBackgroundImage($background->toDTO());
        $this->assertEquals($expected, $actual);
    }

    public function test_updateAvailableCars_withCarModelsArrayShouldAvailableCallCarsDomainService()
    {
        $brand = 'Audi';
        $models = json_decode('{"Audi": [{"brand": "Audi", "name": "A3", "year": "2016", "available": true}, {"brand": "Audi", "name": "A1", "year": "2017", "available": false}] }');
        $expectedModels = array(new CarModelDTO('Audi', 'A3', '2016'));
        $this->availableCarsDomainService->expects($this->once())->method('updateAvailableCarsByDealer')->with(array(new AvailableCars(self::ID, $brand, $expectedModels)), self::ID);
        $this->sut->updateAvailableCars(self::ID, $models);
    }

    public function test_getRating_Should_Call_getReviewsByDealer_In_DealerDomainService(){
        $this->dealerDomainService->expects($this->once())->method("getReviewsByDealer")->with(self::ID);
        $this->sut->getRating(self::ID);
    }

    private function getDealer(): Dealer
    {
        return new Dealer(self::DEALER_NAME, self::ADDRESS, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::EMAIL, self::PASSWORD);
    }


    private function getDataFromAcceptedApplication()
    {
        return DealerApplication::constructAcceptedApplication(self::VENDOR_NAME, self::DEALER_NAME, self::VENDOR_ROLE, self::PHONE, self::EMAIL, self::HOW);
    }


    private function exerciseCreateDealer()
    {
        $this->sut->createDealer(self::TOKEN, self::PASSWORD);
    }

    private function exerciseUpdate()
    {
        $this->sut->updateDealer(self::ID, self::DEALER_NAME, self::DESCRIPTION, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::PASSWORD, self::ADDRESS, self::SCHEDULING, self::DELIVERY_CONDITIONS, self::SPECIAL_CONDITIONS, self::GENERAL_CONDITIONS, $this->avatarFile, $this->backgroundFile, self::ZIP_CODE);
    }

    /**
     * @return DealerWithConditionsDTO
     */
    private function exerciseGetDealerById()
    {
        return $this->sut->getDealerById(self::ID);
    }

    private function configureRegistryAsStub()
    {
        $this->dealerDomainService->expects($this->any())->method('getDealerById')->will($this->returnValue($this->getDealer()));
    }
}
