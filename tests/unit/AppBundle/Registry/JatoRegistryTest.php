<?php

use AppBundle\Registry\JatoRegistry;
use AppBundle\DTO\CarModelDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JatoRegistryTest extends KernelTestCase
{
    const VEHICLE_WITH_PACKS = "773617320160801";
    /**
     * @var JatoRegistry
     */
    private $sut;

    protected function setUp()
    {
        self::bootKernel();
        $this->sut = static::$kernel->getContainer()->get("JatoRegistry");
    }

    public function test_getBrandsWillReturnAllBrands()
    {
        $actual = $this->sut->getBrands();
        $this->assertEquals("[{\"name\":\"Abarth\"},{\"name\":\"Alfa Romeo\"},{\"name\":\"Audi\"},{\"name\":\"Bentley\"},{\"name\":\"BMW\"},{\"name\":\"BYD\"},{\"name\":\"Citro\u00ebn\"},{\"name\":\"Dacia\"},{\"name\":\"DS\"},{\"name\":\"Ferrari\"},{\"name\":\"Fiat\"},{\"name\":\"Ford\"},{\"name\":\"Honda\"},{\"name\":\"Hyundai\"},{\"name\":\"Infiniti\"},{\"name\":\"Jaguar\"},{\"name\":\"Jeep\"},{\"name\":\"Kia\"},{\"name\":\"Lamborghini\"},{\"name\":\"Lancia\"},{\"name\":\"Land Rover\"},{\"name\":\"Lexus\"},{\"name\":\"Maserati\"},{\"name\":\"Mazda\"},{\"name\":\"Mercedes-Benz\"},{\"name\":\"MINI\"},{\"name\":\"Mitsubishi\"},{\"name\":\"Nissan\"},{\"name\":\"Opel\"},{\"name\":\"Peugeot\"},{\"name\":\"Porsche\"},{\"name\":\"Renault\"},{\"name\":\"Rolls-Royce\"},{\"name\":\"SEAT\"},{\"name\":\"Skoda\"},{\"name\":\"Smart\"},{\"name\":\"SsangYong\"},{\"name\":\"Subaru\"},{\"name\":\"Suzuki\"},{\"name\":\"Toyota\"},{\"name\":\"Volkswagen\"},{\"name\":\"Volvo\"}]", json_encode($actual));
    }

    public function test_getBrandsModelsWillReturnAllModels()
    {
        $actual = $this->sut->getBrandsModels(array("Abarth"));
        $this->assertEquals(array_keys($actual), array("Abarth"));
        $expectedArray = array(new CarModelDTO('Abarth', '124 Spider', '2016'),
                               new CarModelDTO('Abarth', '500', '2016'),
                               new CarModelDTO('Abarth', '500C', '2016'));
        $this->assertEquals($expectedArray, $actual["Abarth"]);
    }

    public function test_getPacks_shouldReturnThePacks()
    {
        $this->markTestSkipped();
        $actual = $this->sut->getPacks(self::VEHICLE_WITH_PACKS);
        $this->assertEquals("[{\"id\":1027,\"title\":\" [8ML] Pack Business Plus\",\"description\":\" [RB4] Standard Radio + Navegaci\u00f3n\n [508] Sensores de parking (delantero + trasero)\n [410] Retrovisor interior electrocr\u00f3mico\n\",\"prices\":1900.01,\"type\":\"PVP\",\"hasReadmore\":false,\"includeOptionsName\":[\" [RB4] Standard Radio + Navegaci\u00f3n\",\" [508] Sensores de parking (delantero + trasero)\",\" [410] Retrovisor interior electrocr\u00f3mico\"]},{\"id\":1021,\"title\":\" [6UN] Pack Driver Assistance\",\"description\":\" [508] Sensores de parking (delantero + trasero)\n\",\"prices\":850,\"type\":\"PVP\",\"hasReadmore\":false,\"includeOptionsName\":[\" [508] Sensores de parking (delantero + trasero)\"]}]", json_encode($actual));
    }
}
