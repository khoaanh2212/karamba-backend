<?php

use AppBundle\Entity\ApplianceOffer;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 16:31
 */
class ApplianceOfferRegistryTest extends BaseRegistryTest
{

    const APPLIANCE_ID = "test applianceId";

    const DEALER_ID = "test dealerId";

    protected function getEntities()
    {
        return array(
            $this->getEntity(),
            $this->getEntity(),
            $this->getEntity(),
            $this->getEntity(),
            $this->getEntity(),
        );
    }

    protected function getEntity()
    {
        return new ApplianceOffer(self::DEALER_ID, self::APPLIANCE_ID);
    }

    protected function updateEntity($entity)
    {
        $entity->setDealerId("anotherDealer");
    }

    public function test_expireOffers_calledWithApplianceIdShouldExpireAllOpportunities()
    {
        UUIDGeneratorFactory::reset();
        $appliance1 = $this->getEntity();
        $appliance2 = $this->getEntity();
        $appliance3 = $this->getEntity();
        $appliance4 = $this->getEntity();
        $appliance5 = $this->getEntity();
        $appliance6 = $this->getEntity();
        $this->sut->saveOrUpdate($appliance1);
        $this->sut->saveOrUpdate($appliance2);
        $this->sut->saveOrUpdate($appliance3);
        $this->sut->saveOrUpdate($appliance4);
        $this->sut->saveOrUpdate($appliance5);
        $this->sut->saveOrUpdate($appliance6);
        //GUARD ASSERTION
        $this->assertEquals(6, count($this->sut->findAll()));
        $this->makeAndOffer($appliance1);
        $this->makeAndOffer($appliance2);
        $this->sut->expireOffers(self::APPLIANCE_ID);
        $this->assertEquals(2, count($this->sut->findAll()));
    }

    public function test_findByapplianceId_willReturnCorrectData()
    {
        UUIDGeneratorFactory::reset();
        $appliance1 = $this->getEntity();
        $appliance2 = $this->getEntity();
        $appliance3 = $this->getEntity();
        $appliance4 = $this->getEntity();
        $appliance5 = $this->getEntity();
        $appliance6 = $this->getEntity();
        $this->sut->saveOrUpdate($appliance1);
        $this->sut->saveOrUpdate($appliance2);
        $this->sut->saveOrUpdate($appliance3);
        $this->sut->saveOrUpdate($appliance4);
        $this->sut->saveOrUpdate($appliance5);
        $this->sut->saveOrUpdate($appliance6);
        $actual = $this->sut->findByApplianceId(self::APPLIANCE_ID);
        $this->assertEquals(6, count($actual));
    }

    private function makeAndOffer(ApplianceOffer $offer)
    {
        $offer->makeAnOffer(self::DEALER_ID, 10, 20, false);
        $this->sut->saveOrUpdate($offer);
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("ApplianceOfferRegistry");
    }
}