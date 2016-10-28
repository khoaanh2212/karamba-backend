<?php


use AppBundle\DomainServices\DealerDomainService;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerCondition;
use AppBundle\Registry\DealerConditionsRegistry;
use AppBundle\Registry\DealerRegistry;
use AppBundle\Utils\Point;
use Doctrine\ORM\EntityNotFoundException;

class DealerDomainServiceTest extends PHPUnit_Framework_TestCase
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
    const ID = 1;
    const CONDITION_ID = 2;
    const A_DESCRIPTION = "a description";
    const ZIP_CODE = "zipCode";

    /**
     * @var DealerDomainService
     */
    private $sut;

    /**
     * @var DealerRegistry
     */
    private $registry;

    /**
     * @var DealerConditionsRegistry
     */
    private $dealerConditionRegistry;

    /**
     * @var ReviewRegistry
     */
    private $reviewRegistry;

    /**
     * @var ReviewDetailRegistry
     */
    private $reviewDetailRegistry;

    /**
     * @var Dealer
     */
    private $dummyDealer;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\DealerRegistry")->disableOriginalConstructor()->setMethods(
            array("findAll", "findByIds", "findDealerIdsByModel", "findDealerIdsByModelInPosition", "findOneById", "delete", "saveOrUpdate")
        )->getMock();
        $this->dealerConditionRegistry = $this->getMockBuilder("AppBundle\\Registry\\DealerConditionsRegistry")->disableOriginalConstructor()->setMethods(
            array("findAll", "findOneById", "findAllByIds", "delete", "saveOrUpdate")
        )->getMock();
        $this->reviewRegistry = $this->getMockBuilder("AppBundle\\Registry\\ReviewRegistry")->disableOriginalConstructor()->setMethods(
            array("findBy", "findOneById")
        )->getMock();
        $this->reviewDetailRegistry = $this->getMockBuilder("AppBundle\\Registry\\ReviewDetailRegistry")->disableOriginalConstructor()->setMethods(
            array("findBy", "findOneById")
        )->getMock();
        $this->dummyDealer = $this->getMockBuilder("AppBundle\\Entity\\Dealer")->disableOriginalConstructor()->getMock();
        $this->sut = new DealerDomainService($this->registry, $this->dealerConditionRegistry, $this->reviewRegistry, $this->reviewDetailRegistry);
    }

    public function test_createDealer_withDealerData_willCallToRegistrySaveWithCreatedDealer()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate")->will($this->returnValue($this->getDealer()));
        $this->exerciseCreateDealer();
    }

    public function test_createDealer_withDealerData_willReturnThePersistedDealer()
    {
        $dealer = $this->configureDealerRegistrySaveOrUpdateAsStub();
        $actual = $this->exerciseCreateDealer();
        $this->assertEquals($dealer, $actual);
    }



    public function test_getDealerById_call_registryFindByIdWithTheDealerId()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::ID)->will($this->returnValue($this->getDealer()));
        $this->sut->getDealerById(self::ID);
    }

    public function test_getDealerById_returnEntityReturnedByTheRegistry()
    {
        $dealer = $this->getDealer();
        $this->registry->expects($this->any())->method("findOneById")->with(self::ID)->will($this->returnValue($dealer));
        $actual = $this->sut->getDealerById(self::ID);
        $this->assertEquals($dealer, $actual);
    }

    public function test_updateDealerWillCallToRegistryFindOneById()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::ID)->will($this->returnValue($this->dummyDealer));
        $this->exerciseUpdateDealer();
    }

    /**
     * @expectedException Doctrine\ORM\EntityNotFoundException
     */
    public function test_updateDealer_registryReturnsNull_returnsEntityNotFoundException()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::ID)->will($this->returnValue(null));
        $this->exerciseUpdateDealer();
    }

    public function test_updateDealer_willCallToDealerSetNameWithTheName()
    {
        $this->exerciseVerifyCallToSet("setName", self::DEALER_NAME);
    }

    public function test_updateDealer_willCallToDealerSetPhoneNumberWithThePhone()
    {
        $this->exerciseVerifyCallToSet("setPhoneNumber", self::PHONE);
    }

    public function test_updateDealer_willCAllToDealerSetVendorNameWithTheVendorName()
    {
        $this->exerciseVerifyCallToSet("setVendorName", self::VENDOR_NAME);
    }

    public function test_updateDealer_willCallToDealerSetVendorRoleWithTheVendorRole()
    {
        $this->exerciseVerifyCallToSet("setVendorRole", self::VENDOR_ROLE);
    }

    public function test_updateDealer_willCallToDealerSetPasswordWithThePassword()
    {
        $this->exerciseVerifyCallToSet("setPassword", self::PASSWORD);
    }

    public function test_updateDealer_willCallToDealerSetScheduleWithTheSchedule()
    {
        $this->exerciseVerifyCallToSet("setSchedule", self::SCHEDULING);
    }

    public function test_updateDealer_willCallToSetAddressWithTheAddress()
    {
        $this->exerciseVerifyCallToSet("setAddress", self::ADDRESS);
    }

    public function test_updateDealer_willCallToDealerSetDeliveryConditionsWithTheConditions()
    {
        $this->exerciseVerifyCallToSet("setDeliveryConditions", self::DELIVERY_CONDITIONS);
    }

    public function test_updateDealer_willCallToDealerSetSpecialConditionsWithTheConditions()
    {
        $this->exerciseVerifyCallToSet("setSpecialConditions", self::SPECIAL_CONDITIONS);
    }

    public function test_updateDealer_willCallToDealersetDescription()
    {
        $this->exerciseVerifyCallToSet("setDescription", self::A_DESCRIPTION);
    }

    public function test_updateDealer_withDealerData_andDealerConditions_willCallToDealerConditionRegistryfindAllByIds()
    {
        $this->configureRegistryAsStub();
        $this->configureDealerRegistrySaveOrUpdateAsStub();
        $this->dealerConditionRegistry->expects($this->once())->method("findAllByIds")->with(array("1","2","3"));
        $this->exerciseUpdateDealer();
    }

    public function test_updateDealer_withDealerData_andDealerConditions_callToPersistedDealerAddGeneralConditionWithTheCondition()
    {
        $this->configureRegistryAsStub();
        $this->configureDealerRegistrySaveOrUpdateAsStub($this->dummyDealer);
        $condition = $this->getDealerCondition();
        $this->dealerConditionRegistry->expects($this->any())->method("findAllByIds")->will($this->returnValue(array($condition)));
        $this->dummyDealer->expects($this->once())->method("addGeneralCondition")->with($condition);
        $this->exerciseUpdateDealer();
    }

    public function test_updateDealer_withDealerData_andDealerConditions_callToPersistedDealerAddGeneralConditionWithAllTheConditions()
    {
        $this->configureRegistryAsStub();
        $this->configureDealerRegistrySaveOrUpdateAsStub($this->dummyDealer);
        $condition = $this->getDealerCondition();
        $this->dealerConditionRegistry->expects($this->any())->method("findAllByIds")->will($this->returnValue(array($condition, $condition, $condition)));
        $this->dummyDealer->expects($this->exactly(3))->method("addGeneralCondition");
        $this->exerciseUpdateDealer();
    }

    public function test_updateDealer_withDealerData_andDealerConditions_callToPersistDealerTwice()
    {
        $this->configureRegistryAsStub();
        $this->configureDealerRegistrySaveOrUpdateAsStub($this->dummyDealer);
        $condition = $this->getDealerCondition();
        $this->dealerConditionRegistry->expects($this->any())->method("findAllByIds")->will($this->returnValue(array($condition, $condition, $condition)));
        $this->registry->expects($this->once())->method("saveOrUpdate")->will($this->returnValue($this->getDealer()));
        $this->exerciseUpdateDealer();
    }

    public function test_findDealerIdsByModelInPosition_called_withCorrectParametersShouldCallToRegistryWithCorrectParameters()
    {
        $point = new Point(1, 1);
        $point2 = new Point(2, 2);
        $this->registry->expects($this->once())->method("findDealerIdsByModelInPosition")->with("brand", "model", $point, $point2);
        $this->sut->findDealerIdsByModelInPosition("brand", "model", $point, $point2);
    }

    public function test_findDealerIdsByModelInPosition_called_willReturnDealersInPosition()
    {
        $dealersInPosition = array(1, 2, 3);
        $point = new Point(1, 1);
        $point2 = new Point(2, 2);
        $this->registry->expects($this->any())->method("findDealerIdsByModelInPosition")->will($this->returnValue($dealersInPosition));
        $actual = $this->sut->findDealerIdsByModelInPosition("brand", "model", $point, $point2);
        $this->assertEquals($dealersInPosition, $actual);
    }

    public function test_findDealerIdsByModel_called_callFindDealerIdsByModelWithCorrectParameters()
    {
        $this->registry->expects($this->once())
                ->method("findDealerIdsByModel")
                ->with("brand", "model");
        $this->sut->findDealerIdsByModel("brand", "model");
    }

    public function test_findByIds_willCallRegistryFindByIds()
    {
        $dealerIds = array(1, 2, 3);
        $this->registry->expects($this->once())
                ->method("findByIds")
                ->with($dealerIds);
        $this->sut->findByIds($dealerIds);
    }



    private function exerciseVerifyCallToSet($method, $value)
    {
        $this->configureRegistryAsStub();
        $this->dummyDealer->expects($this->once())
            ->method($method)
            ->with($value);
        $this->exerciseUpdateDealer();
    }



    private function getDealer(): Dealer
    {
        return new Dealer(self::DEALER_NAME, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::EMAIL, self::PASSWORD, self::SCHEDULING, self::DELIVERY_CONDITIONS, self::SPECIAL_CONDITIONS);
    }

    private function getDealerCondition($id = null)
    {
        if(!$id) {
            $id = self::ID;
        }
        $dealerCondition = new DealerCondition("testCondition");
        $dealerCondition->setId($id);
        return $dealerCondition;
    }

    /**
     * @return Dealer
     */
    private function exerciseCreateDealer(array $generalConditions = null)
    {
        return $this->sut->createDealer(self::DEALER_NAME, self::ADDRESS, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::EMAIL, self::PASSWORD, self::SCHEDULING, self::DELIVERY_CONDITIONS, self::SPECIAL_CONDITIONS, $generalConditions);
    }

    private function configureDealerRegistrySaveOrUpdateAsStub(Dealer $dealer = null)
    {
        if(!$dealer) {
            $dealer = $this->getDealer();
        }
        $this->registry->expects($this->any())->method("saveOrUpdate")->will($this->returnValue($dealer));
        return $dealer;
    }

    private function exerciseUpdateDealer()
    {
        $this->sut->updateDealer(self::ID, self::DEALER_NAME, self::A_DESCRIPTION, self::PHONE, self::VENDOR_NAME, self::VENDOR_ROLE, self::PASSWORD, self::ADDRESS, self::SCHEDULING, self::DELIVERY_CONDITIONS, self::SPECIAL_CONDITIONS, array(1,2,3), self::ZIP_CODE, new Point());
    }

    private function configureRegistryAsStub()
    {
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->dummyDealer));
    }
}