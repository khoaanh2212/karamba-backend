<?php

use AppBundle\DTO\AvailableCarModelDTO;
use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\Entity\AvailableCars;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\Price;
use AppBundle\Entity\StockCar;
use AppBundle\Utils\Point;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class DealerRegistryTest extends BaseRegistryTest
{

    private $conditionRegistry;
    private $dealerCondition;

    protected function setUp()
    {
        parent::setUp();
        $this->conditionRegistry = static::$kernel->getContainer()->get("DealerConditionRegistry");
        $this->dealerCondition = $this->conditionRegistry->findOneById(1);
    }

    protected function truncateDb()
    {
        $this->sut->truncateDb();
        $this->sut->truncateDb("dealer_conditions_assoc");
    }

    protected function getEntities()
    {
        $return = array();
        array_push($return, $this->constructDealer("mail1"));
        array_push($return, $this->constructDealer("mail2"));
        array_push($return, $this->constructDealer("mail3"));
        array_push($return, $this->constructDealer("mail4"));
        return $return;
    }

    protected function getEntity()
    {
        $dealer = $this->constructDealer();
        $persisted = $this->sut->saveOrUpdate($dealer);
        $persisted->addGeneralCondition($this->dealerCondition);
        return $persisted;
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }


    public function test_findDealerIdsByModelInPosition_returnsCorrectDealers()
    {
        list($dealer1, $dealer2, $dealer3, $dealer4, $searchBrand, $searchModel, $searchSquare) = $this->configureSearchCarsByDealersInPosition();
        $actual = $this->sut->findDealerIdsByModelInPosition($searchBrand, $searchModel, $searchSquare[0], $searchSquare[1]);
        $this->assertTrue(count($actual) == 2);
        $this->assertTrue(in_array($dealer1->getId(), $actual));
        $this->assertTrue(in_array($dealer3->getId(), $actual));
    }

    public function test_findByIds_returnsCorrectDealers()
    {
        list($dealer1, $dealer2, $dealer3, $dealer4, $searchBrand, $searchModel, $searchSquare) = $this->configureSearchCarsByDealersInPosition();
        $id1 = $dealer1->getId();
        $id2 = $dealer2->getId();
        $id3 = $dealer4->getId();
        $actual = $this->sut->findByIds(array($id1, $id2, $id3));
        $this->assertEquals(3, count($actual));
    }

    public function test_findDealerIds_returnCorrectDealers()
    {
        list($dealer1, $dealer2, $dealer3, $dealer4, $searchBrand, $searchModel, $searchSquare) = $this->configureSearchCarsByDealersInPosition();
        $actual = $this->sut->findDealerIdsByModel($searchBrand, $searchModel);
        $this->assertTrue(count($actual) == 3);
        $this->assertTrue(in_array($dealer1->getId(), $actual));
        $this->assertTrue(in_array($dealer3->getId(), $actual));
        $this->assertTrue(in_array($dealer4->getId(), $actual));
    }

    public function test_findDealersIdsByModelInPositionAnotherCar_returnsCorrectDealers()
    {
        list($dealer1, $dealer2, $dealer3, $dealer4, $searchBrand, $searchModel, $searchSquare, $anotherBrand, $anotherModel) = $this->configureSearchCarsByDealersInPosition();
        $actual = $this->sut->findDealerIdsByModelInPosition($anotherBrand, $anotherModel, $searchSquare[0], $searchSquare[1]);
        $this->assertTrue(count($actual) == 1);
        $this->assertTrue(in_array($dealer2->getId(), $actual));
    }

    public function test_findDealersClientInAnotherPosition_returnsCorrectDealers()
    {
        list($dealer1, $dealer2, $dealer3, $dealer4, $searchBrand, $searchModel, $searchSquare, $anotherBrand, $anotherModel) = $this->configureSearchCarsByDealersInPosition();
        $clientPosition = new Point(2.5569436, 41.7546793);
        $squareSearch = $clientPosition->getSquareCoordinates(15);
        $actual = $this->sut->findDealerIdsByModelInPosition($searchBrand, $searchModel, $squareSearch[0], $squareSearch[1]);
        $this->assertTrue(count($actual) == 1);
        $this->assertTrue(in_array($dealer4->getId(), $actual));
    }

    private function createStockCar(string $dealerId, string $brand, string $model): StockCar
    {
        return new StockCar($dealerId, $this->createVehicleDTO($brand, $model), $this->createVehicleOptionDTO(), array(), new Price(1,1,1), "photo");
    }

    private function createVehicleDTO(string $brand, string $model): VehicleDTO
    {
        return new VehicleDTO(1, $brand, $brand, $model, $model, 2016, "Flux Condenser", "Flux Condenser", "derivative", "4x4", 5, "derivative display", "much money such impressive", "wow much money");
    }

    private function createVehicleOptionDTO(): VehicleOptionDTO
    {
        return new VehicleOptionDTO(1, "option", "optiontype", 12.0, "such price");
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("DealerRegistry");
    }

    /**
     * @return Dealer
     */
    private function constructDealer($email = null, $longitude = null, $latitude = null)
    {
        if(!$email) {
            $email = "email@email";
        }
        if(!$longitude) {
            $longitude = 11.1;
        }
        if(!$latitude) {
            $latitude = 11.2;
        }
        return new Dealer("dealer name", "0034932094578", "vendor name", "role", $email, "password", "openallnight", "delivery cond", "special cond", "test addr", "test desc", "zipcode", new Point($longitude, $latitude));
    }

    protected function updateEntity($entity)
    {
        // TODO: Implement updateEntity() method.
    }

    /**
     * @return array
     */
    private function configureSearchCarsByDealersInPosition()
    {
        UUIDGeneratorFactory::reset();
        $stockCarRegistry = static::$kernel->getContainer()->get("StockCarsRegistry");
        $availableCarRegistry = static::$kernel->getContainer()->get("AvailableCarsRegistry");
        $stockCarRegistry->truncateDb();
        $availableCarRegistry->truncateDb();
        //same city
        $dealer1 = $this->sut->saveOrUpdate($this->constructDealer("mail1", 2.196618, 41.4378689));
        $dealer2 = $this->sut->saveOrUpdate($this->constructDealer("mail2", 2.1720423, 41.4433367));
        //nearby city
        $dealer3 = $this->sut->saveOrUpdate($this->constructDealer("mail3", 2.1045283, 41.3607303));
        //different city
        $dealer4 = $this->sut->saveOrUpdate($this->constructDealer("mail4", 2.5569436, 41.7546793));
        $searchBrand = "Audi";
        $searchModel = "A3";
        $anotherBrand = "Ferrari";
        $searchModel = "A3";
        $availableCarsModels = new CarModelDTO($searchBrand, $searchModel, 2015);
        $notSearchModel = "Testarrossa";
        $notCarModels = new CarModelDTO($anotherBrand, $notSearchModel, 2015);
        $stockCarRegistry->saveOrUpdate($this->createStockCar($dealer1->getId(), $searchBrand, $searchModel));
        $stockCarRegistry->saveOrUpdate($this->createStockCar($dealer2->getId(), $anotherBrand, $notSearchModel));
        $stockCarRegistry->saveOrUpdate($this->createStockCar($dealer4->getId(), $searchBrand, $searchModel));
        $availableCarRegistry->saveOrUpdate(new AvailableCars($dealer3->getId(), $searchBrand, array(new AvailableCarModelDTO($searchBrand, $searchModel, 2016, true))));
        $clientPosition = new Point(2.196618, 41.4378689);
        $searchSquare = $clientPosition->getSquareCoordinates(15);
        return array($dealer1,$dealer2, $dealer3,$dealer4, $searchBrand, $searchModel, $searchSquare, $anotherBrand, $notSearchModel);
    }
}