<?php


use AppBundle\ApplicationServices\DealerApplicationService;
use AppBundle\DomainServices\DealerApplicationDomainService;
use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\DealerConfirmationMail;
use AppBundle\Entity\PendingDealerApplication;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/TestUUID.php';

class DealerApplicationServiceTest extends PHPUnit_Framework_TestCase
{

    const VENDOR_NAME = "vendor name";
    const DEALER_NAME = "dealer name";
    const VENDOR_ROLE = "vendor role";
    const PHONE = "phone";
    const EMAIL = "email";
    const HOW = "how";

    const TEST_ID = "TEST_ID";
    const TOKEN = "token";
    /**
     * @var DealerApplicationService
     */
    private $sut;

    /**
     * @var DealerApplicationDomainService
     */
    private $dealerDomainService;

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    /**
     * @var DealerConfirmationMail
     */
    private $dummyMailer;

    protected function setUp()
    {
        $this->dealerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\DealerApplicationDomainService")
                                            ->disableOriginalConstructor()
                                            ->getMock();
        $this->dummyMailer = $this->getMockBuilder("AppBundle\Entity\DealerConfirmationMail")->disableOriginalConstructor()->getMock();
        $this->uuidGenerator = new TestUUID(self::TEST_ID);
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->sut = new DealerApplicationService($this->dealerDomainService, $this->dummyMailer);
    }




    public function test_listAllPendingApplications_call_toDomainService_listAllPendingApplications()
    {
        $this->dealerDomainService->expects($this->once())
                ->method("listAllPendingApplications");
        $this->sut->listAllPendingApplications();
    }

    public function test_listAllPendingApplications_willReturnCollectionOfPendingApplicationsDTO()
    {
        $this->dealerDomainService->expects($this->any())
                ->method("listAllPendingApplications")
                ->will($this->returnValue($this->getPendingApplications()));
        $actual = $this->sut->listAllPendingApplications();
        $this->assertEquals('[{"id":"TEST_ID","vendorName":"vendor1","name":"dealer name","vendorRole":"vendor role","phone":"phone number","email":"email@email.com","howArrivedHere":"how"},{"id":"TEST_ID","vendorName":"vendor2","name":"dealer name","vendorRole":"vendor role","phone":"phone number","email":"email@email.com","howArrivedHere":"how"},{"id":"TEST_ID","vendorName":"vendor3","name":"dealer name","vendorRole":"vendor role","phone":"phone number","email":"email@email.com","howArrivedHere":"how"}]', json_encode($actual));
    }

    public function test_rejectApplication_willCallToDomain_rejectApplication()
    {
        $this->dealerDomainService->expects($this->once())->method("rejectApplication")->with(self::TEST_ID);
        $this->sut->rejectApplication(self::TEST_ID);
    }

    public function test_acceptApplication_willCallToDomainService_acceptApplication()
    {
        $this->dealerDomainService->expects($this->once())->method("acceptApplication")->with(self::TEST_ID)->will($this->returnValue($this->getAcceptedApplication()));
        $this->sut->acceptApplication(self::TEST_ID);
    }

    public function test_acceptApplication_willCallToMailerSendCorrectData()
    {
        $this->dealerDomainService->expects($this->any())->method("acceptApplication")->will($this->returnValue($this->getAcceptedApplication()));
        $this->dummyMailer->expects($this->once())->method("send")->with(self::EMAIL, self::DEALER_NAME, self::VENDOR_NAME, self::TEST_ID);
        $this->sut->acceptApplication(self::TEST_ID);
    }

    public function test_createApplication_callToDomainServiceCreateApplication()
    {
        $this->dealerDomainService->expects($this->once())->method("createApplication")->with(self::DEALER_NAME, self::VENDOR_NAME, self::VENDOR_ROLE, self::PHONE, self::EMAIL, self::HOW);
        $this->sut->createApplication(self::DEALER_NAME, self::VENDOR_NAME, self::VENDOR_ROLE, self::PHONE, self::EMAIL, self::HOW);
    }


    public function test_retrieveApplicationAndValidate_callDomainretrieveApplicationAndValidate_withToken()
    {
        $this->dealerDomainService->expects($this->once())->method("retrieveApplicationAndValidate")->with(self::TOKEN)
            ->will($this->returnValue($this->getAcceptedApplication()));
        $this->sut->retrieveApplicationAndValidate(self::TOKEN);
    }

    public function test_retrieveApplicationAndValidate_willReturnDTO()
    {
        $this->dealerDomainService->expects($this->once())->method("retrieveApplicationAndValidate")->with(self::TOKEN)
            ->will($this->returnValue($this->getAcceptedApplication()));
        $actual = $this->sut->retrieveApplicationAndValidate(self::TOKEN);
        $this->assertEquals('{"id":"TEST_ID","vendorName":"vendor name","name":"dealer name","vendorRole":"vendor role","phone":"phone","email":"email","howArrivedHere":"how"}', json_encode($actual));
    }


    private function getAcceptedApplication($email = null)
    {
        if(!$email) {
            $email = self::EMAIL;
        }
        return DealerApplication::constructAcceptedApplication(self::VENDOR_NAME, self::DEALER_NAME, "vendor role", "phone", $email, "how");
    }

    /**
     * @return PendingDealerApplication[]
     */
    private function getPendingApplications() : array
    {
        $return = array();
        array_push($return, $this->getPendingDealerApplication("vendor1"));
        array_push($return, $this->getPendingDealerApplication("vendor2"));
        array_push($return, $this->getPendingDealerApplication("vendor3"));
        return $return;
    }

    /**
     * @return PendingDealerApplication
     */
    private function getPendingDealerApplication(string $vendorName)
    {
        return DealerApplication::constructPendingApplication($vendorName, "dealer name", "vendor role", "phone number", "email@email.com", "how");
    }

}