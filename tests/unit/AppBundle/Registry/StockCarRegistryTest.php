<?php

use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\Price;
use AppBundle\Entity\StockCar;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class StockCarRegistryTest extends BaseRegistryTest
{
    /**
     * @var Dealer
     */
    private $dealer;
    private $dummyCarModel;
    private $dummyVehicle;
    private $dummyColor;
    private $dummyExtras;

    protected function setUp()
    {
        parent::setUp();
        $this->dummyVehicle = new VehicleDTO(1, 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test');
        $this->dummyColor = new VehicleOptionDTO(1, 'test', 'test', 12, "12 €");
        $this->dummyPrice = new Price(1000, 850, 15);
        $this->dummyExtras = array();

        $this->dealer = new Dealer("dealer name", "0034932094578", "vendor name", "role", "testemail", "password", "openallnight", "delivery cond", "special cond", "address");
        $dealerRegistry = static::$kernel->getContainer()->get("DealerRegistry");
        $dealerRegistry->saveOrUpdate($this->dealer);
    }

    protected function getEntities()
    {
        $return = array();
        array_push($return, $this->constructStockCar());
        array_push($return, $this->constructStockCar());
        return $return;
    }

    protected function getEntity()
    {
        $car = $this->constructStockCar();
        $persisted = $this->sut->saveOrUpdate($car);
        return $persisted;
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("StockCarsRegistry");
    }

    /**
     * @return StockCar
     */
    private function constructStockCar()
    {
        return new StockCar($this->dealer->getId(),$this->dummyVehicle, $this->dummyColor,  $this->dummyExtras, $this->dummyPrice, "dummyurl");
    }

    protected function updateEntity($entity)
    {
        // TODO: Implement updateEntity() method.
    }
}
