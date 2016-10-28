<?php


use AppBundle\DomainServices\DealerApplicationDomainService;
use AppBundle\Entity\AcceptedDealerApplication;
use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\PendingDealerApplication;
use AppBundle\Registry\DealerApplicationRegistry;
use AppBundle\Registry\PendingDealerApplicationRegistry;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/TestUUID.php';

class DealerApplicationDomainServiceTest extends PHPUnit_Framework_TestCase
{
    CONST TEST_ID = "test id";
    const VENDOR_NAME = "vendor name";
    const DEALER_NAME = "dealer name";
    const VENDOR_ROLE = "vendor role";
    const PHONE = "phone";
    const EMAIL = "email";
    const HOW = "how";
    const A_TOKEN = "a token";
    /**
     * @var DealerApplicationDomainService
     */
    private $sut;

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    /**
     * @var DealerApplicationRegistry
     */
    private $registry;

    /**
     * @var AcceptedDealerApplication
     */
    private $dummyAccepted;

    protected function setUp()
    {
        $this->uuidGenerator = new TestUUID(self::TEST_ID);
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\DealerApplicationRegistry")->disableOriginalConstructor()->setMethods(
            array("findAll", "findAllPending", "findOneByToken", "findOneById", "delete", "saveOrUpdate")
        )->getMock();
        $this->sut = new DealerApplicationDomainService($this->registry);
        $this->dummyAccepted = $this->getMockBuilder("AppBundle\Entity\AcceptedDealerApplication")->getMock();
    }


    public function test_listAllPendingApplications_willCallTo_pendingRegistryfindAll()
    {
        $this->registry->expects($this->once())->method("findAllPending")->will($this->returnValue(array()));
        $this->sut->listAllPendingApplications();
    }

    public function test_listAllPendingApplications_willReturnValueFromRegistry()
    {
        $value = array();
        $this->registry->expects($this->any())->method("findAllPending")->will($this->returnValue($value));
        $this->assertEquals($value, $this->sut->listAllPendingApplications());
    }

    public function test_acceptApplication_calledWithId_callToRepository_findOneById()
    {
        $this->registry->expects($this->once())
            ->method("findOneById")->with(self::TEST_ID)->will($this->returnValue($this->getPendingApplication()));
        $this->exerciseAccept();
    }

    public function test_acceptApplication_calledWithId_applicationExists_callToApplicationAccept()
    {
        $pendingApplication = $this->getMockBuilder("AppBundle\\Entity\\PendingDealerApplication")->disableOriginalConstructor()->getMock();
        $acceptedApplication = DealerApplication::constructAcceptedApplication("vendor", "dealer", "role", self::PHONE, self::EMAIL, self::HOW, "token", new \DateTime());
        $this->registry->expects($this->any())
            ->method("findOneById")->will($this->returnValue($pendingApplication));
        $pendingApplication->expects($this->once())->method("accept")->will($this->returnValue($acceptedApplication));
        $this->exerciseAccept();
    }

    public function test_acceptApplication_calledWithId_applicationExists_callRepositorySaveOrUpdateWithAccepted()
    {
        $pendingApplication = $this->getMockBuilder("AppBundle\\Entity\\PendingDealerApplication")->disableOriginalConstructor()->getMock();
        $acceptedApplication = DealerApplication::constructAcceptedApplication("vendor", "dealer", "role", self::PHONE, self::EMAIL, self::HOW, "token");
        $this->registry->expects($this->any())
            ->method("findOneById")->will($this->returnValue($pendingApplication));
        $pendingApplication->expects($this->any())->method("accept")->will($this->returnValue($acceptedApplication));
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($acceptedApplication);
        $this->exerciseAccept();
    }

    public function test_processApplication_whenCalledWithAcceptedApplication_thenProcessesApplication(){
        $accepted = $this->getMockBuilder("AppBundle\\Entity\\AcceptedDealerApplication")->disableOriginalConstructor()->getMock();
        $accepted->expects($this->exactly(1))->method("process");
        $this->exerciseProcess($accepted);
    }

    public function test_processApplication_whenCalledWithAcceptedApplication_thenCallsRepoWithProcessedApplication(){
        $processed = $this->getProcessedApplication();
        $accepted = $this->getMockBuilder("AppBundle\\Entity\\AcceptedDealerApplication")->disableOriginalConstructor()->getMock();
        $accepted->expects($this->exactly(1))->method("process")->will($this->returnValue($processed));
        $this->registry->expects($this->once())
            ->method("saveOrUpdate");
        $this->exerciseProcess($accepted);
    }

    public function test_rejectApplication_calledWithId_callToRepository_findOneById()
    {
        $this->registry->expects($this->once())
            ->method("findOneById")->with(self::TEST_ID)->will($this->returnValue($this->getPendingApplication()));
        $this->exerciseReject();
    }

    /**
     * @expectedException Doctrine\ORM\EntityNotFoundException
     */
    public function test_rejectApplication_calledWithId_applicationDoesNotExist_raiseException()
    {
        $this->registry->expects($this->any())
            ->method("findOneById")->will($this->returnValue(null));
        $this->exerciseReject();
    }
    public function test_rejectApplication_calledWithId_applicationExists_callToApplicationReject()
    {
        $pendingApplication = $this->getMockBuilder("AppBundle\\Entity\\PendingDealerApplication")->disableOriginalConstructor()->getMock();
        $rejected = DealerApplication::constructRejectedApplication("vendor", "dealer", "role", self::PHONE, self::EMAIL, self::HOW, "token", new \DateTime());
        $this->registry->expects($this->any())
            ->method("findOneById")->will($this->returnValue($pendingApplication));
        $pendingApplication->expects($this->once())->method("reject")->will($this->returnValue($rejected));
        $this->exerciseReject();
    }

    public function test_rejectApplication_calledWithId_applicationExists_callRepositorySaveOrUpdateWithRejected()
    {
        $pendingApplication = $this->getMockBuilder("AppBundle\\Entity\\PendingDealerApplication")->disableOriginalConstructor()->getMock();
        $rejected = DealerApplication::constructRejectedApplication("vendor", "dealer", "role", self::PHONE, self::EMAIL, self::HOW, "token");
        $this->registry->expects($this->any())
            ->method("findOneById")->will($this->returnValue($pendingApplication));
        $pendingApplication->expects($this->any())->method("reject")->will($this->returnValue($rejected));
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($rejected);
        $this->exerciseReject();
    }

    public function test_createApplication_callRegistrySaveOrUpdateWithPendingApplication()
    {
        $this->registry->expects($spy = $this->any())
            ->method('saveOrUpdate');
        $this->sut->createApplication(self::DEALER_NAME, self::VENDOR_NAME, self::VENDOR_ROLE, self::PHONE, self::EMAIL, self::HOW);
        $this->assertEquals(1, $spy->getInvocationCount());
        $invocations = $spy->getInvocations();
        $saved = $invocations[0]->parameters[0];
        $this->assertContains("AppBundle\Entity\DealerApplication>>[id]:test id,[vendorName]:dealer name,[dealerName]:vendor name,[vendorRole]:vendor role,[phoneNumber]:phone,[email]:email,[howArrived]:how,[token]:,[expiration]:", "".$saved);
    }

    public function test_retrieveApplicationAndValidate_callRegistryFindOneByToken_withThePassedToken()
    {
        $this->registry->expects($this->once())
                ->method('findOneByToken')
                ->with(self::A_TOKEN)->will($this->returnValue($this->getAcceptedApplication()));
        $this->exerciseRetrieveApplicationAndValidate();
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function test_retrieveApplicationAndValidate_ifNotTokenIsPresent_raiseException()
    {
        $this->registry->expects($this->any())
            ->method('findOneByToken')
            ->will($this->returnValue(null));
        $this->exerciseRetrieveApplicationAndValidate();
    }

    public function test_retrieveApplicationAndValidate_ifTokenIsPresent_callIsTokenValid()
    {
        $this->registry->expects($this->once())
            ->method('findOneByToken')
            ->with(self::A_TOKEN)->will($this->returnValue($this->dummyAccepted));
        $this->dummyAccepted->expects($this->once())->method("checkValidToken");
        $this->exerciseRetrieveApplicationAndValidate();
    }

    public function test_retrieveApplicationAndValidate_ifTokenReturnsTrue_returnsApplication()
    {
        $this->registry->expects($this->once())
            ->method('findOneByToken')
            ->with(self::A_TOKEN)->will($this->returnValue($this->dummyAccepted));
        $this->dummyAccepted->expects($this->any())->method("checkValidToken")->will($this->returnValue(true));
        $actual = $this->exerciseRetrieveApplicationAndValidate();
        $this->assertEquals($this->dummyAccepted, $actual);
    }

    protected function getPendingApplication(): PendingDealerApplication
    {
        return DealerApplication::constructPendingApplication("vendor", "dealer", "role", self::PHONE, self::EMAIL, self::HOW);
    }

    private function getAcceptedApplication($email = null)
    {
        if(!$email) {
            $email = "email";
        }
        return DealerApplication::constructAcceptedApplication("vendor name", "dealer name", "vendor role", "phone", $email, "how", "token", new \DateTime());
    }
    private function getProcessedApplication()
    {
        return DealerApplication::constructProcessedApplication("vendorName", "dealerName", "vendorRole", "phone", "some@email.com", "how", "token", new \DateTime());
    }
    private function exerciseReject()
    {
        $this->sut->rejectApplication(self::TEST_ID);
    }

    private function exerciseAccept()
    {
        $this->sut->acceptApplication(self::TEST_ID);
    }
    private function exerciseProcess(AcceptedDealerApplication $accepted)
    {
        $this->sut->processApplication($accepted);
    }
    private function exerciseRetrieveApplicationAndValidate()
    {
        return $this->sut->retrieveApplicationAndValidate(self::A_TOKEN);
    }
}