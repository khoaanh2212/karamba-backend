<?php


use AppBundle\Entity\ApplianceOffer;

class ApplianceOfferTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "dealerId";
    const APPLIANCE_ID = "applianceId";
    const CASHPRICE = 10.0;
    const FOUNDEDPRICE = 20.10;
    const INSTOCK = true;
    /**
     * @var ApplianceOffer
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new ApplianceOffer(self::DEALER_ID, self::APPLIANCE_ID);
    }

    public function test_makeAnOfferShouldChangeTheState()
    {
        $this->markTestSkipped();
        //GUARD ASSERTION
        $this->assertEquals("{\"id\":\"TEST_ID\",\"dealerId\":\"dealerId\",\"applianceId\":\"applianceId\",\"state\":\"new_opportunity\",\"cashPrice\":null,\"foundedPrice\":null,\"inStock\":null,\"isRead\":false,\"dealerInfo\":null}", json_encode($this->sut->toDTO()));
        $this->sut->makeAnOffer(self::DEALER_ID, self::CASHPRICE, self::FOUNDEDPRICE, self::INSTOCK);
        $this->assertEquals("{\"dealerId\":\"dealerId\",\"applianceId\":\"applianceId\",\"state\":\"sent_offer\",\"cashPrice\":\"10\",\"foundedPrice\":\"20.1\",\"inStock\":true,\"isRead\":false,\"dealerInfo\":null}", json_encode($this->sut->toDTO()));
    }

    /**
     * @expectedException \SecurityException
     */
    public function test_makeAnOfferWithDifferentDealerShouldRaiseException()
    {
        $this->sut->makeAnOffer("another dealer", self::CASHPRICE, self::FOUNDEDPRICE, self::INSTOCK);
    }
}