<?php


use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DTO\ExtrasDTO;
use AppBundle\Entity\CarAppliance;
use AppBundle\Factory\CarApplianceFactory;
use AppBundle\Registry\CarApplianceRegistry;
use AppBundle\Utils\JatoAccessor;
use AppBundle\Utils\LoggedJatoClient;
use Doctrine\ORM\EntityNotFoundException;

class CarApplianceDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const VEHICLE_ID = self::PACKAGE_ID;
    const TEST_BRAND = "test brand";
    const TEST_MODEL = "Test model";
    const TEST_COLOR = 5;
    const CLIENT_ID = "client id";
    const PACKAGE_ID = 14;
    const FIRST_EXTRA = 1;
    const SECOND_EXTRA = self::NUMBER_OF_DOORS;
    const THIRD_EXTRA = 3;
    const FOURTH_EXTRA = 4;
    const PRICE = 22222;
    const ID = "ID";
    const DERIVATIVE = "1.7 Tbi 240CV TCT";
    const NUMBER_OF_DOORS = 2;
    const TRANSMISSION = "A";
    const ENGINE_TYPE = "alto octanaje sin plomo";
    const IMAGE = "somephoto";
    /**
     * @var CarApplianceDomainService
     */
    private $sut;

    /**
     * @var CarApplianceRegistry
     */
    private $carApplianceRegistry;

    /**
     * @var JatoAccessor
     */
    private $jatoAccessor;

    /**
     * @var LoggedJatoClient
     */
    private $loggedApiClient;

    /**
     * @var CarApplianceFactory
     */
    private $carApplianceFactory;

    protected function setUp()
    {
        $this->carApplianceRegistry = $this->getMockBuilder("AppBundle\\Registry\\CarApplianceRegistry")
            ->setMethods(array("findByClientId","delete", "findOneById", "saveOrUpdate"))
            ->disableOriginalConstructor()
            ->getMock();
        $this->jatoAccessor = $this->getMockBuilder("AppBundle\\Utils\\JatoAccessor")->disableOriginalConstructor()->getMock();
        $this->loggedApiClient = $this->getMockBuilder("AppBundle\\Utils\\LoggedJatoClient")->disableOriginalConstructor()->getMock();
        $this->jatoAccessor->expects($this->any())->method("login")->will($this->returnValue($this->loggedApiClient));
        $this->carApplianceFactory = $this->getMockBuilder("AppBundle\\Factory\\CarApplianceFactory")->disableOriginalConstructor()->getMock();
        $this->sut = new CarApplianceDomainService($this->carApplianceRegistry, $this->jatoAccessor, $this->carApplianceFactory);
    }

    public function test_createApplianceWithCorrectDataShouldCallJatoClientgetVehicleDetailWithTheVehicleId()
    {

        $this->loggedApiClient->expects($this->once())->method("getVehiclePacksAndExtras")->with(self::VEHICLE_ID)->will($this->returnValue($this->getCarDetail()));
        $this->loggedApiClient->expects($this->any())->method("getVehicle")->will($this->returnValue($this->returnVehicleData()));
        $this->exerciseCreateAppliance();
    }

    private function returnVehicleData()
    {
        return json_decode('{"databaseName":"SSCE_CS2002","name":"Espa\u00f1a","flagUrl":"https:\/\/sslphotos.jato.com\/Flags\/E.png","vehicles":[{"vehicleId":'.self::VEHICLE_ID.',"vehicleUid":7256861,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"4C","modelNameLocal":"4C","modelKey":"4C","modelNameToDisplay":"4C","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"2","numberOfDoorsToDisplay":"2","shortBodyName":"coup\u00e9","bodyCode":"CO","bodyCodeToDisplay":"CO","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":66200,"priceToDisplay":"\u20ac66,200","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.7 Tbi 240CV TCT","derivativeToDisplay":"1.7 Tbi 240CV TCT","engine":"1.7","transmission":"A","trimLevel":"-","trimLevelToDisplay":"-","seats":"2","liters":1.7,"manufacturerCode":"643.110.0","maximumPowerKw":177,"maximumPowerhpPs":240,"vehicleType":"C"},{"vehicleId":752880720160801,"vehicleUid":7528807,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"4C","modelNameLocal":"4C","modelKey":"4C","modelNameToDisplay":"4C","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"2","numberOfDoorsToDisplay":"2","shortBodyName":"targa","bodyCode":"TA","bodyCodeToDisplay":"TA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":79550,"priceToDisplay":"\u20ac79,550","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"Spider 1.7 Tbi 240cv TCT","derivativeToDisplay":"Spider 1.7 Tbi 240cv TCT","engine":"1.7","transmission":"A","trimLevel":"-","trimLevelToDisplay":"-","seats":"2","liters":1.7,"manufacturerCode":"643.310.0","maximumPowerKw":177,"maximumPowerhpPs":240,"vehicleType":"C"},{"vehicleId":773617320160901,"vehicleUid":7736173,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":38500,"priceToDisplay":"\u20ac38,500","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"2.0 Gasolina 200cv Giulia AT","derivativeToDisplay":"2.0 Gasolina 200cv Giulia AT","engine":"2.0","transmission":"A","trimLevel":"Giulia","trimLevelToDisplay":"Giulia","seats":"5","liters":2,"manufacturerCode":"620.GR0.0","maximumPowerKw":147,"maximumPowerhpPs":200,"vehicleType":"C"},{"vehicleId":773617220160901,"vehicleUid":7736172,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":40000,"priceToDisplay":"\u20ac40,000","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"2.0 Gasolina 200cv Super AT","derivativeToDisplay":"2.0 Gasolina 200cv Super AT","engine":"2.0","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2,"manufacturerCode":"620.PR0.0","maximumPowerKw":147,"maximumPowerhpPs":200,"vehicleType":"C"},{"vehicleId":769070420160901,"vehicleUid":7690704,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":33149,"priceToDisplay":"\u20ac33,150","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 136cv Giulia","derivativeToDisplay":"2.2 Diesel 136cv Giulia","engine":"2.1","transmission":"M","trimLevel":"Giulia","trimLevelToDisplay":"Giulia","seats":"5","liters":2.1,"manufacturerCode":"620.GRC.1","maximumPowerKw":100,"maximumPowerhpPs":136,"vehicleType":"C"},{"vehicleId":769070620160901,"vehicleUid":7690706,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":34550,"priceToDisplay":"\u20ac34,550","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 150cv Giulia","derivativeToDisplay":"2.2 Diesel 150cv Giulia","engine":"2.1","transmission":"M","trimLevel":"Giulia","trimLevelToDisplay":"Giulia","seats":"5","liters":2.1,"manufacturerCode":"620.GRE.0","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":769070520160901,"vehicleUid":7690705,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":37049,"priceToDisplay":"\u20ac37,050","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 150cv Giulia AT","derivativeToDisplay":"2.2 Diesel 150cv Giulia AT","engine":"2.1","transmission":"A","trimLevel":"Giulia","trimLevelToDisplay":"Giulia","seats":"5","liters":2.1,"manufacturerCode":"620.GRG.0","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":769071120160901,"vehicleUid":7690711,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":36050,"priceToDisplay":"\u20ac36,050","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 150cv Super","derivativeToDisplay":"2.2 Diesel 150cv Super","engine":"2.1","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2.1,"manufacturerCode":"620.PRE.0","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":769071020160901,"vehicleUid":7690710,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":38550,"priceToDisplay":"\u20ac38,550","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 150cv Super AT","derivativeToDisplay":"2.2 Diesel 150cv Super AT","engine":"2.1","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2.1,"manufacturerCode":"620.PRG.0","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":769070920160901,"vehicleUid":7690709,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":37850,"priceToDisplay":"\u20ac37,850","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 180cv Super","derivativeToDisplay":"2.2 Diesel 180cv Super","engine":"2.1","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2.1,"manufacturerCode":"620.PRJ.0","maximumPowerKw":132,"maximumPowerhpPs":180,"vehicleType":"C"},{"vehicleId":769070820160901,"vehicleUid":7690708,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":40350,"priceToDisplay":"\u20ac40,350","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 Diesel 180cv Super AT","derivativeToDisplay":"2.2 Diesel 180cv Super AT","engine":"2.1","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2.1,"manufacturerCode":"620.PRL.0","maximumPowerKw":132,"maximumPowerhpPs":180,"vehicleType":"C"},{"vehicleId":769070720160901,"vehicleUid":7690707,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":86600,"priceToDisplay":"\u20ac86,600","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"2.9T V6 510cv Quadrifoglio","derivativeToDisplay":"2.9T V6 510cv Quadrifoglio","engine":"2.9","transmission":"M","trimLevel":"Quadrifoglio","trimLevelToDisplay":"Quadrifoglio","seats":"4","liters":2.9,"manufacturerCode":"620.QRU.0","maximumPowerKw":375,"maximumPowerhpPs":510,"vehicleType":"C"},{"vehicleId":775088420161001,"vehicleUid":7750884,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"4","drivenWheelsToDisplay":"4x4","price":54000,"priceToDisplay":"\u20ac54,000","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"2.0 GME T4 280cv Veloce ATX","derivativeToDisplay":"2.0 GME T4 280cv Veloce ATX","engine":"2.0","transmission":"A","trimLevel":"Veloce","trimLevelToDisplay":"Veloce","seats":"5","liters":2,"manufacturerCode":"620.DA1.0","maximumPowerKw":206,"maximumPowerhpPs":280,"vehicleType":"C"},{"vehicleId":775088520161001,"vehicleUid":7750885,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"4","drivenWheelsToDisplay":"4x4","price":51150,"priceToDisplay":"\u20ac51,150","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.2 JTDM 210cv Veloce ATX","derivativeToDisplay":"2.2 JTDM 210cv Veloce ATX","engine":"2.1","transmission":"A","trimLevel":"Veloce","trimLevelToDisplay":"Veloce","seats":"5","liters":2.1,"manufacturerCode":"620.DAP.0","maximumPowerKw":154,"maximumPowerhpPs":210,"vehicleType":"C"},{"vehicleId":773617120160901,"vehicleUid":7736171,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIA","modelNameLocal":"Giulia","modelKey":"Giulia","modelNameToDisplay":"Giulia","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"4","numberOfDoorsToDisplay":"4","shortBodyName":"sed\u00e1n","bodyCode":"SA","bodyCodeToDisplay":"SA","vehiclePhotoUrl":null,"drivenWheels":"R","drivenWheelsToDisplay":"trasero","price":89100,"priceToDisplay":"\u20ac89,100","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"2.9T V6 510cv Quadrifoglio AT","derivativeToDisplay":"2.9T V6 510cv Quadrifoglio AT","engine":"2.9","transmission":"A","trimLevel":"Quadrifoglio","trimLevelToDisplay":"Quadrifoglio","seats":"4","liters":2.9,"manufacturerCode":"620.QRV.0","maximumPowerKw":375,"maximumPowerhpPs":510,"vehicleType":"C"},{"vehicleId":766376520160801,"vehicleUid":7663765,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":23600,"priceToDisplay":"\u20ac23,600","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.6 JTD 120CV Giulietta","derivativeToDisplay":"1.6 JTD 120CV Giulietta","engine":"1.6","transmission":"M","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":1.6,"manufacturerCode":"191.B5R.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":754917220160801,"vehicleUid":7549172,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":25350,"priceToDisplay":"\u20ac25,350","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.6 JTD 120CV Super","derivativeToDisplay":"1.6 JTD 120CV Super","engine":"1.6","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.6,"manufacturerCode":"191.C5R.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":765699220160801,"vehicleUid":7656992,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":25600,"priceToDisplay":"\u20ac25,600","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.6 JTD 120CV TCT Giulietta","derivativeToDisplay":"1.6 JTD 120CV TCT Giulietta","engine":"1.6","transmission":"A","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":1.6,"manufacturerCode":"191.B59.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":765698820160801,"vehicleUid":7656988,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":27350,"priceToDisplay":"\u20ac27,350","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.6 JTD 120CV TCT Super","derivativeToDisplay":"1.6 JTD 120CV TCT Super","engine":"1.6","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.6,"manufacturerCode":"191.C59.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":732716120160801,"vehicleUid":7327161,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":37880,"priceToDisplay":"\u20ac37,880","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.7 TB 240 CV Veloce TCT","derivativeToDisplay":"1.7 TB 240 CV Veloce TCT","engine":"1.7","transmission":"A","trimLevel":"Veloce","trimLevelToDisplay":"Veloce","seats":"5","liters":1.7,"manufacturerCode":"191.V5C.2","maximumPowerKw":177,"maximumPowerhpPs":240,"vehicleType":"C"},{"vehicleId":765699320160801,"vehicleUid":7656993,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":25050,"priceToDisplay":"\u20ac25,050","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.0 JTD 150CV Giulietta","derivativeToDisplay":"2.0 JTD 150CV Giulietta","engine":"2.0","transmission":"M","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":2,"manufacturerCode":"191.B5S.2","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":765698720160801,"vehicleUid":7656987,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":26800,"priceToDisplay":"\u20ac26,800","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.0 JTD 150CV Super","derivativeToDisplay":"2.0 JTD 150CV Super","engine":"2.0","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2,"manufacturerCode":"191.C5S.2","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":765698620160801,"vehicleUid":7656986,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":29950,"priceToDisplay":"\u20ac29,950","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"2.0 JTD 175CV TCT Super","derivativeToDisplay":"2.0 JTD 175CV TCT Super","engine":"2.0","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":2,"manufacturerCode":"191.C5T.2","maximumPowerKw":128,"maximumPowerhpPs":175,"vehicleType":"C"},{"vehicleId":765699120160801,"vehicleUid":7656991,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":27450,"priceToDisplay":"\u20ac27,450","fuelType":"G","fuelTypeToDisplay":"LPG","derivative":"1.4 TB 120CV GPL Super","derivativeToDisplay":"1.4 TB 120CV GPL Super","engine":"1.4","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.4,"manufacturerCode":"191.CGE.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":765699420160801,"vehicleUid":7656994,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":24900,"priceToDisplay":"\u20ac24,900","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.4 TB MAIR 150CV MT Giulietta","derivativeToDisplay":"1.4 TB MAIR 150CV MT Giulietta","engine":"1.4","transmission":"M","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":1.4,"manufacturerCode":"191.B5N.2","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":765699020160801,"vehicleUid":7656990,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":26650,"priceToDisplay":"\u20ac26,650","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.4 TB MAIR 150CV Super","derivativeToDisplay":"1.4 TB MAIR 150CV Super","engine":"1.4","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.4,"manufacturerCode":"191.C5N.2","maximumPowerKw":110,"maximumPowerhpPs":150,"vehicleType":"C"},{"vehicleId":765698520160801,"vehicleUid":7656985,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":27950,"priceToDisplay":"\u20ac27,950","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.4 TB MAIR 170CV TCT Giulietta","derivativeToDisplay":"1.4 TB MAIR 170CV TCT Giulietta","engine":"1.4","transmission":"A","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":1.4,"manufacturerCode":"191.B5G.2","maximumPowerKw":125,"maximumPowerhpPs":170,"vehicleType":"C"},{"vehicleId":765698920160801,"vehicleUid":7656989,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":29700,"priceToDisplay":"\u20ac29,700","fuelType":"P","fuelTypeToDisplay":"alto octanaje sin plomo","derivative":"1.4 TB MAIR 170CV TCT Super","derivativeToDisplay":"1.4 TB MAIR 170CV TCT Super","engine":"1.4","transmission":"A","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.4,"manufacturerCode":"191.C5G.2","maximumPowerKw":125,"maximumPowerhpPs":170,"vehicleType":"C"},{"vehicleId":717538220160801,"vehicleUid":7175382,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":23200,"priceToDisplay":"\u20ac23,200","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 TB 120CV Giulietta","derivativeToDisplay":"1.4 TB 120CV Giulietta","engine":"1.4","transmission":"M","trimLevel":"Giulietta","trimLevelToDisplay":"Giulietta","seats":"5","liters":1.4,"manufacturerCode":"191.B5E.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":754917320160801,"vehicleUid":7549173,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"GIULIETTA","modelNameLocal":"Giulietta","modelKey":"Giulietta","modelNameToDisplay":"Giulietta","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"5","numberOfDoorsToDisplay":"5","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":24950,"priceToDisplay":"\u20ac24,950","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 TB 120CV Super","derivativeToDisplay":"1.4 TB 120CV Super","engine":"1.4","transmission":"M","trimLevel":"Super","trimLevelToDisplay":"Super","seats":"5","liters":1.4,"manufacturerCode":"191.C5E.2","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":773651620160701,"vehicleUid":7736516,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":23100,"priceToDisplay":"\u20ac23,100","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 TB MULTIAIR 140 CV TCT SUPER","derivativeToDisplay":"1.4 TB MULTIAIR 140 CV TCT SUPER","engine":"1.4","transmission":"A","trimLevel":"SUPER","trimLevelToDisplay":"SUPER","seats":"5","liters":1.4,"manufacturerCode":"145.E3G.3","maximumPowerKw":103,"maximumPowerhpPs":140,"vehicleType":"C"},{"vehicleId":773651420160701,"vehicleUid":7736514,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":26500,"priceToDisplay":"\u20ac26,500","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 TB MULTIAIR 170 CV TCT VELOCE","derivativeToDisplay":"1.4 TB MULTIAIR 170 CV TCT VELOCE","engine":"1.4","transmission":"A","trimLevel":"VELOCE","trimLevelToDisplay":"VELOCE","seats":"5","liters":1.4,"manufacturerCode":"145.V3D.3","maximumPowerKw":125,"maximumPowerhpPs":170,"vehicleType":"C"},{"vehicleId":773651320160701,"vehicleUid":7736513,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":20500,"priceToDisplay":"\u20ac20,500","fuelType":"G","fuelTypeToDisplay":"LPG","derivative":"1.4 T-JET 120 CV GLP SUPER","derivativeToDisplay":"1.4 T-JET 120 CV GLP SUPER","engine":"1.4","transmission":"M","trimLevel":"SUPER","trimLevelToDisplay":"SUPER","seats":"5","liters":1.4,"manufacturerCode":"145.E3U.3","maximumPowerKw":88,"maximumPowerhpPs":120,"vehicleType":"C"},{"vehicleId":773652020160701,"vehicleUid":7736520,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":17950,"priceToDisplay":"\u20ac17,950","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"0.9 TWINAIR 105 CV MITO","derivativeToDisplay":"0.9 TWINAIR 105 CV MITO","engine":"0.9","transmission":"M","trimLevel":"MITO","trimLevelToDisplay":"MITO","seats":"5","liters":0.9,"manufacturerCode":"145.B3B.3","maximumPowerKw":77,"maximumPowerhpPs":105,"vehicleType":"C"},{"vehicleId":773651720160701,"vehicleUid":7736517,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":19050,"priceToDisplay":"\u20ac19,050","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"0.9 TWINAIR 105 CV SUPER","derivativeToDisplay":"0.9 TWINAIR 105 CV SUPER","engine":"0.9","transmission":"M","trimLevel":"SUPER","trimLevelToDisplay":"SUPER","seats":"5","liters":0.9,"manufacturerCode":"145.E3B.3","maximumPowerKw":77,"maximumPowerhpPs":105,"vehicleType":"C"},{"vehicleId":773651920160701,"vehicleUid":7736519,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":19000,"priceToDisplay":"\u20ac19,000","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.3 JTDM 95 CV MITO","derivativeToDisplay":"1.3 JTDM 95 CV MITO","engine":"1.2","transmission":"M","trimLevel":"MITO","trimLevelToDisplay":"MITO","seats":"5","liters":1.2,"manufacturerCode":"145.B3R.3","maximumPowerKw":70,"maximumPowerhpPs":95,"vehicleType":"C"},{"vehicleId":773651520160701,"vehicleUid":7736515,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":20100,"priceToDisplay":"\u20ac20,100","fuelType":"D","fuelTypeToDisplay":"diesel","derivative":"1.3 JTDM 95 CV SUPER","derivativeToDisplay":"1.3 JTDM 95 CV SUPER","engine":"1.2","transmission":"M","trimLevel":"SUPER","trimLevelToDisplay":"SUPER","seats":"5","liters":1.2,"manufacturerCode":"145.E3R.3","maximumPowerKw":70,"maximumPowerhpPs":95,"vehicleType":"C"},{"vehicleId":773652120160701,"vehicleUid":7736521,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":16950,"priceToDisplay":"\u20ac16,950","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 78 CV MITO","derivativeToDisplay":"1.4 78 CV MITO","engine":"1.4","transmission":"M","trimLevel":"MITO","trimLevelToDisplay":"MITO","seats":"5","liters":1.4,"manufacturerCode":"145.B37.3","maximumPowerKw":57,"maximumPowerhpPs":78,"vehicleType":"C"},{"vehicleId":773651820160701,"vehicleUid":7736518,"makeNameGlobal":"ALFA ROMEO","makeNameLocal":"Alfa Romeo","makeKey":"Alfa Romeo","makeNameToDisplay":"Alfa Romeo","modelNameGlobal":"MITO","modelNameLocal":"MiTo","modelKey":"MiTo","modelNameToDisplay":"MiTo","modelYear":"2016","modelYearToDisplay":"2016","numberOfDoors":"3","numberOfDoorsToDisplay":"3","shortBodyName":"berlina con port\u00f3n","bodyCode":"HA","bodyCodeToDisplay":"HA","vehiclePhotoUrl":null,"drivenWheels":"F","drivenWheelsToDisplay":"delantero","price":18050,"priceToDisplay":"\u20ac18,050","fuelType":"U","fuelTypeToDisplay":"sin plomo","derivative":"1.4 78 CV SUPER","derivativeToDisplay":"1.4 78 CV SUPER","engine":"1.4","transmission":"M","trimLevel":"SUPER","trimLevelToDisplay":"SUPER","seats":"5","liters":1.4,"manufacturerCode":"145.E37.3","maximumPowerKw":57,"maximumPowerhpPs":78,"vehicleType":"C"}]}');
    }

    public function test_createApplianceWithCorrectDataShouldCallToFactoryWithCorrectData()
    {
        $this->loggedApiClient->expects($this->any())->method("getVehiclePacksAndExtras")->will($this->returnValue($this->getCarDetail()));
        $this->loggedApiClient->expects($this->any())->method("getVehicle")->will($this->returnValue($this->returnVehicleData()));
        $package = new ExtrasDTO(self::PACKAGE_ID, "[PB4] Paquete Black line", 708.46);
        $color = new ExtrasDTO(self::TEST_COLOR, "[9VS] Color Azul Celeste radiante", 0.0);
        $extras = array(
            new ExtrasDTO(1, "[KA2] Cámara trasera", 481.68000000000001),
            new ExtrasDTO(2, "[7X5] APS. Sistema de aparcamiento acústico plus con indicación selectiva y asistencia deaparcamiento", 1057.72),
            new ExtrasDTO(3, "[9VD] Equipo de sonido Audi Sound System", 299.61000000000001),
            new ExtrasDTO(4, "[9VS] Equipo de sonido Bang & Oluffsen", 928.60000000000002),
        );
        $this->carApplianceFactory->expects($this->once())->method("constructCarAppliance")
            ->with(self::CLIENT_ID, self::VEHICLE_ID, self::TEST_BRAND, self::TEST_MODEL, self::DERIVATIVE, self::NUMBER_OF_DOORS, self::TRANSMISSION, self::ENGINE_TYPE, self::PRICE, self::IMAGE, $extras, null, $color);
        $this->exerciseCreateAppliance();
    }


    public function test_createApplianceWithCorrectDataShouldPersistTheAppliance()
    {
        $this->loggedApiClient->expects($this->any())->method("getVehiclePacksAndExtras")->will($this->returnValue($this->getCarDetail(), true));
        $this->loggedApiClient->expects($this->any())->method("getVehicle")->will($this->returnValue($this->returnVehicleData()));
        $this->carApplianceRegistry->expects($this->once())->method("saveOrUpdate");
        $this->exerciseCreateAppliance();
    }

    public function test_getAppliancesForClient_shouldCallToRegistryWithTheClientId()
    {
        $this->carApplianceRegistry->expects($this->once())->method("findByClientId")->with(self::CLIENT_ID);
        $this->sut->getAppliancesForClient(self::CLIENT_ID);
    }

    public function test_delete_callsRegistryDeleteMethod()
    {
        $carAppliance = new CarAppliance("id",1,"BMW","X6","der", self::NUMBER_OF_DOORS,"trans","motor",1,"",array());
        $this->carApplianceRegistry->expects($this->once())
            ->method("findOneById")->will($this->returnValue($carAppliance));
        $this->carApplianceRegistry->expects($this->exactly(1))->method("delete");
        $this->sut->delete(self::ID);
    }

    public function test_delete_throwsException_whenEntityNotFound()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->carApplianceRegistry->expects($this->once())
            ->method("findOneById")->will($this->returnValue(null));
        $this->sut->delete(self::ID);
    }

    public function test_getApplianceById_willCallToRegistryFindOneById()
    {
        $this->carApplianceRegistry->expects($this->once())
                ->method("findOneById")
                ->with(self::ID);
        $this->sut->getApplianceById(self::ID);
    }

    private function getCarDetail()
    {
        $data = json_decode('{
            "price": 22222,
            "photo": "somephoto",
            "options": {
              "packs": {
                "14": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 1154,
                  "optionType": "O",
                  "optionName": " [PB4] Paquete Black line",
                  "currencyCode": null,
                  "attributes": ["Includes:, Incluye:", "[4ZD] Paquete brillo estilo negro", "And", "[VW1] Cristales oscuros en parte trasera", "And", "[6FJ] Carcasa de los retrovisores exteriores en negro Audi exclusive"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [1, 2],
                  "requires": [],
                  "excludes": [],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 708.46,
                  "basePrice903": 585.5,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Others",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 708.46,
                  "priceChange": null,
                  "displayPrice": "\u20ac708",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "PB4"
                }
              },
              "extras": {
                "0": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 1,
                  "optionType": "O",
                  "optionName": " [KA2] C\u00e1mara trasera",
                  "currencyCode": null,
                  "attributes": ["Sensores de aparcamiento traseros con c\u00e1mara", "Sistema de asistencia de aparcamiento trasero con visualizaci\u00f3n de gu\u00eda", "Requires:, Requiere:", "[7X2] APS. Sistema de aparcamiento ac\u00fastico plus con indicaci\u00f3n selectiva (delantero y trasero)", "Or", "[7X5] APS. Sistema de aparcamiento ac\u00fastico plus con indicaci\u00f3n selectiva y asistencia deaparcamiento"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [],
                  "requires": [{
                    "optionId": [2]
                  }],
                  "excludes": [],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 481.68,
                  "basePrice903": 398.08,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Equipamiento",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 481.68,
                  "priceChange": null,
                  "displayPrice": "\u20ac482",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "KA2"
                },
                "1": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 2,
                  "optionType": "O",
                  "optionName": " [7X5] APS. Sistema de aparcamiento ac\u00fastico plus con indicaci\u00f3n selectiva y asistencia deaparcamiento",
                  "currencyCode": null,
                  "attributes": ["Sensores de aparcamiento delanteros y traseros con radar", "Sistema de asistencia de aparcamiento trasero con aparcamiento autom\u00e1tico total", "Informacion Espacio para Parking"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [],
                  "requires": [],
                  "excludes": [1050, 1172],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 1057.72,
                  "basePrice903": 874.15,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Equipamiento",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 1057.72,
                  "priceChange": null,
                  "displayPrice": "\u20ac1,058",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "7X5"
                },
                "2": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 3,
                  "optionType": "O",
                  "optionName": " [9VD] Equipo de sonido Audi Sound System",
                  "currencyCode": null,
                  "attributes": ["Diez altavoces de tipo mejorado con subwoofer", "Elimina: Rueda de repuesto de menor tama\u00f1o que el resto"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [],
                  "requires": [],
                  "excludes": [1176],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 299.61,
                  "basePrice903": 247.61,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Audio y comunicaci\u00f3n",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 299.61,
                  "priceChange": null,
                  "displayPrice": "\u20ac300",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "9VD"
                },
                "3": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 4,
                  "optionType": "O",
                  "optionName": " [9VS] Equipo de sonido Bang & Oluffsen",
                  "currencyCode": null,
                  "attributes": ["Catorce altavoces de tipo mejorado ( Bang & Olufsen ) con subwoofer y sonido surround", "Elimina: Rueda de repuesto de menor tama\u00f1o que el resto"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [],
                  "requires": [],
                  "excludes": [1175],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 928.6,
                  "basePrice903": 767.44,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Audio y comunicaci\u00f3n",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 928.6,
                  "priceChange": null,
                  "displayPrice": "\u20ac929",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "9VS"
                },
                "4": {
                  "optionImage": "https:\/\/sslphotos.jato.com\/OptionIcons\/option.png",
                  "vehicleId": 770434720160601,
                  "optionId": 5,
                  "optionType": "C",
                  "optionName": " [9VS] Color Azul Celeste radiante",
                  "currencyCode": null,
                  "attributes": ["Catorce altavoces de tipo mejorado ( Bang & Olufsen ) con subwoofer y sonido surround", "Elimina: Rueda de repuesto de menor tama\u00f1o que el resto"],
                  "specsDatabaseName": "SSCE_CS2002",
                  "includes": [],
                  "requires": [1,3],
                  "excludes": [4],
                  "ifNotbuiltRequiredInfo": [],
                  "ifBuiltRequires": [],
                  "priceChanges": [],
                  "discountOptionInfos": [],
                  "excludedByIncludingOptionInfos": [],
                  "includesOptions": null,
                  "requiresOptions": null,
                  "excludesOptions": null,
                  "ifNotBuiltOptions": null,
                  "priceChangeOptions": null,
                  "discountOptions": null,
                  "retailPrice902": 928.6,
                  "basePrice903": 767.44,
                  "countryPrice904": null,
                  "countryPrice905": null,
                  "retailPriceWithDelivery906": null,
                  "categoryName": "Color exterior",
                  "translatedCategoryName": null,
                  "optionTypeName": "Option",
                  "price": 0.0,
                  "priceChange": null,
                  "displayPrice": "\u20ac929",
                  "startDate": null,
                  "endDate": null,
                  "optionState": 0,
                  "unbuild": false,
                  "requiredBy": [],
                  "includedBy": [],
                  "changedPricedOptionIds": [],
                  "changingChangedOptionInfos": [],
                  "removedRequiredOptions": null,
                  "optionCode": "9VS"
                }
              }
            }
        }', true);
        $data["options"]["packs"] = json_decode(json_encode($data["options"]["packs"]));
        $data["options"]["extras"] = json_decode(json_encode($data["options"]["extras"]));
        return $data;
    }

    /**
     * @return array
     */
    private function getExtras()
    {
        return array(self::FIRST_EXTRA, self::SECOND_EXTRA, self::THIRD_EXTRA, self::FOURTH_EXTRA);
    }

    private function exerciseCreateAppliance()
    {
        $this->sut->createAppliance(self::CLIENT_ID, self::VEHICLE_ID, self::TEST_BRAND, self::TEST_MODEL, $this->getExtras(), self::PACKAGE_ID, self::TEST_COLOR);
    }
}