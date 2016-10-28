<?php
use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\Entity\ApplianceOffer;
use AppBundle\Entity\CarAppliance;
use AppBundle\Registry\ApplianceOfferRegistry;
use AppBundle\Registry\CarApplianceRegistry;

/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/08/16
 * Time: 12:28
 */
class ApplianceOfferDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "dealer id";
    const APPLIANCE_ID = "applianceId";
    const ANOTHER_DEALER_ID = "another dealer id";
    const OFFER_ID = "id";
    const CASH_PRICE = 10.0;
    const FOUND_PRICE = 20.1;
    const IN_STOCK = true;
    const APPLIANCE_OFFER_ID = "some id";
    /**
     * @var ApplianceOfferDomainService
     */
    private $sut;

    private $applianceOffer;

    /**
     * @var CarApplianceRegistry
     */
    private $carApplianceRegistry;

    /**
     * @var ApplianceOfferRegistry
     */
    private $registry;

    /**
     * @var CarAppliance
     */
    private $carAppliance;

    protected function setUp()
    {
        $this->carAppliance = $this->getMockBuilder("AppBundle\\Entity\\CarAppliance")
            ->disableOriginalConstructor()
            ->getMock();
        $this->applianceOffer = $this->getMockBuilder("AppBundle\\Entity\\ApplianceOffer")
            ->disableOriginalConstructor()
            ->getMock();
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\ApplianceOfferRegistry")
            ->disableOriginalConstructor()
            ->setMethods(array("saveOrUpdate", "findOneById", "findByApplianceId"))
            ->getMock();
        $this->carApplianceRegistry = $this->getMockBuilder("AppBundle\\Registry\\CarApplianceRegistry")
            ->disableOriginalConstructor()
            ->setMethods(array("findOneApplianceOffer", "findOneById", "saveOrUpdate"))
            ->getMock();
        $this->sut = new ApplianceOfferDomainService($this->registry, $this->carApplianceRegistry);
    }

    public function test_createApplianceOffer_withCorrectData_shouldCallToRegistrySaveOrUpdate()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate");
        $this->sut->createOfferForCarAppliance(self::DEALER_ID, self::APPLIANCE_ID);
    }

    public function test_createOffersForCarAppliance_callToRegistryForEveryDealerId()
    {
        $this->registry->expects($this->exactly(2))->method("saveOrUpdate");
        $this->sut->createOffersForCarAppliance(array(self::DEALER_ID, self::ANOTHER_DEALER_ID), self::APPLIANCE_ID);
    }

    public function test_makeAnOffer_callToRegistryGetById()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->registry->expects($this->once())->method("findOneById")->with(self::OFFER_ID)->will($this->returnValue($this->getApplianceOffer()));;
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAnOffer_willCallCarApplianceRegistryWithTheApplianceId()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->applianceOffer->expects($this->any())->method("getApplianceId")->will($this->returnValue(self::APPLIANCE_ID));
        $this->carApplianceRegistry->expects($this->once())->method("findOneById")->with(self::APPLIANCE_ID);
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAndOffer_willCallAppliance_isAvailableForOffers()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->exactly(2))->method("isAvailableForOffers");
        $this->exerciseMakeAnOffer();
    }

    /**
     * @expectedException Doctrine\Common\Proxy\Exception\InvalidArgumentException
     */
    public function test_makeAndOffer_AndIsAvailableForOffersWillThrowException()
    {
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(false));
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAndOffer_AndIsAvailableIsTrueWillCallToAddOffer()
    {
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->carAppliance->expects($this->once())->method("addOffer");
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAndOffer_AndIsAvailableIsTrueWillCallToCarApplianceRegistrySaveOrUpdate()
    {
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->carApplianceRegistry->expects($this->once())->method("saveOrUpdate")->with($this->carAppliance);
        $this->exerciseMakeAnOffer();
    }


    public function test_makeAnOffer_willCallToApplianceOfferMakeAnOfferCorrectData()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->applianceOffer->expects($this->once())->method("makeAnOffer")->with(self::DEALER_ID, self::CASH_PRICE, self::FOUND_PRICE, self::IN_STOCK);
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAnOffer_willCallToRegistrySave()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($this->applianceOffer);
        $this->exerciseMakeAnOffer();
    }

    public function test_makeAnOffer_callCarApplianceRegistryfindOneApplianceOfferWithTheApplianceOfferId()
    {
        $this->carApplianceRegistry->expects($this->any())->method("findOneById")->will($this->returnValue($this->carAppliance));
        $this->carAppliance->expects($this->any())->method("isAvailableForOffers")->will($this->returnValue(true));
        $this->registry->expects($this->any())->method("findOneById")->will($this->returnValue($this->getApplianceOffer()));
        $this->carApplianceRegistry->expects($this->once())->method("findOneApplianceOffer")->with(self::OFFER_ID);
        $this->exerciseMakeAnOffer();
    }

    public function test_markAsRead_callToRegistryGetById()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::OFFER_ID)->will($this->returnValue($this->getApplianceOffer()));;
        $this->exerciseMarkAsRead();
    }

    public function test_markAsRead_callToMarkAsRead()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::OFFER_ID)->will($this->returnValue($this->getApplianceOffer()));;
        $this->applianceOffer->expects($this->once())->method("markAsRead");
        $this->exerciseMarkAsRead();
    }

    public function test_markAsRead_willCallToRegistrySave()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::OFFER_ID)->will($this->returnValue($this->getApplianceOffer()));;
        $this->applianceOffer->expects($this->once())->method("markAsRead");
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($this->applianceOffer);
        $this->exerciseMarkAsRead();
    }

    public function test_findAllOffersForAppliance_shouldCallRegistryfindByApplianceId()
    {
        $this->markTestSkipped();
        $this->registry->expects($this->once())->method("findByApplianceId")->with(self::APPLIANCE_ID);
        $this->sut->findAllOffersForAppliance(self::APPLIANCE_ID);
    }

    /**
     * @return ApplianceOffer
     */
    private function getApplianceOffer()
    {
        return $this->applianceOffer;
    }

    private function exerciseMakeAnOffer()
    {
        $this->sut->makeAnOffer(self::DEALER_ID, self::OFFER_ID, self::CASH_PRICE, self::FOUND_PRICE, self::IN_STOCK);
    }

    private function exerciseMarkAsRead()
    {
        $this->sut->markAsRead(self::OFFER_ID);
    }
}