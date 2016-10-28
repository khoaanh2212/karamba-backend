<?php


use AppBundle\DTO\CarModelDTO;
use AppBundle\Entity\AvailableCars;
use AppBundle\Entity\Dealer;
use AppBundle\Registry\DealerRegistry;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class AvailableCarsRegistryTest extends BaseRegistryTest
{
    /**
     * @var Dealer
     */
    private $dealer;

    /**
     * @var DealerRegistry
     */
    private $dealerRegistry;

    protected function setUp()
    {
        parent::setUp();
        $this->dealer = new Dealer("dealer name", "0034932094578", "vendor name", "role", "testemail", "password", "openallnight", "delivery cond", "special cond", "address");
        $dealerRegistry = static::$kernel->getContainer()->get("DealerRegistry");
        $dealerRegistry->saveOrUpdate($this->dealer);
    }


    public function test_saveOrUpdate_nonExistingEntity_insertsTheEntity()
    {
        $retrievedEntity = $this->sut->findOneByDealerId($this->getId());
        //GUARD ASSERTION
        $this->assertNull($retrievedEntity);
        $entityToPersist = $this->getEntity();
        $persistedEntity = $this->persistAndRetrievePassedEntity($entityToPersist);
        $this->checkPersistedVersusActual($entityToPersist, $persistedEntity);
    }

    public function test_delete_existingEntity_deletesTheEntity()
    {
        $persistedEntity = $this->persistAndRetrievePassedEntity($this->getEntity());
        //GUARD ASSERTION
        $this->assertNotNull($persistedEntity);
        $this->sut->delete($persistedEntity);
        $deletedEntity = $this->sut->findOneByDealerId($this->getId());
        $this->assertNull($deletedEntity);
    }

    public function test_findAll_returnAllPersistedEntities()
    {
        UUIDGeneratorFactory::reset();
        $entities = $this->getEntities();
        $this->persistRecords($entities);
        $actual = $this->sut->findAll();
        $this->countRegistries($entities, $actual);
    }


    protected function persistAndRetrievePassedEntity($entity)
    {
        $this->sut->saveOrUpdate($entity);
        return $this->sut->findOneByDealerId($this->getId());
    }

    protected function getEntities()
    {
        return array(
            $this->constructCar('audi'),
            $this->constructCar('abarth'),
            $this->constructCar('alfa romeo'),
            $this->constructCar('lancia'),
        );
    }

    protected function getEntity()
    {
        return $this->constructCar();
    }

    protected function model($brand = "brand", $name = "name", $year = "year")
    {
        return new CarModelDTO($brand, $name, $year);
    }

    protected function updateEntity($entity)
    {
        $entity->setModels(array($this->model(), $this->model()));
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("AvailableCarsRegistry");
    }

    /**
     * @return AvailableCars
     */
    private function constructCar($brand = "brand")
    {
        return new AvailableCars(self::TEST_ID, $brand, array($this->model()));
    }
}
