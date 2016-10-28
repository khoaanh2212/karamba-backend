<?php

use AppBundle\DTO\ExtrasDTO;
use AppBundle\Entity\CarAppliance;

class CarApplianceTest extends PHPUnit_Framework_TestCase
{
    const PRICE = 12.0;
    const IMAGE_URL = "image url";
    const DERIVATIVE = "derivative";
    const NUMBER_OF_DOORS = 4;
    const TRANSMISSION = "manual";
    const ENGINE = "diesel";
    /**
     * @var CarAppliance
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new CarAppliance("client_id", 1, "test brand", "test model", self::DERIVATIVE, self::NUMBER_OF_DOORS, self::TRANSMISSION, self::ENGINE, self::PRICE, self::IMAGE_URL, array(new ExtrasDTO(1, "test extra", 2.0)));
    }

    public function test_isAvailableForOffers_willReturnTrueWhenAttemptsAreZero()
    {
        $this->assertTrue($this->sut->isAvailableForOffers());
    }

    public function test_isAvailableForOffers_whenSomeOffersArrivedButNotArrivedToMax_willReturnTrue()
    {
        $this->sut->addOffer();
        $this->assertTrue($this->sut->isAvailableForOffers());
    }

    public function test_isAvailableForOffers_whenOffersArrivedToMax_willReturnFalse()
    {
        $this->sut->addOffer();
        $this->sut->addOffer();
        $this->sut->addOffer();
        $this->sut->addOffer();
        $this->sut->addOffer();
        $this->assertFalse($this->sut->isAvailableForOffers());
    }

    public function test_toDTO_returnsCorrectContract()
    {
        $this->markTestSkipped();
        $actual = $this->sut->toDTO();
        $actual->id="testid";
        $this->assertEquals("{\"id\":\"testid\",\"brand\":\"test brand\",\"photo\":\"image url\",\"model\":\"test model\",\"packageName\":null,\"extrasName\":[\"test extra\"],\"color\":null,\"numberOfOffers\":0}", json_encode($actual));
    }
}