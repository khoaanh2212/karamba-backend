<?php


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Utils\RegistryBase;
use AppBundle\Utils\UUIDGenerator;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__.'/TestUUID.php';

abstract class BaseRegistryTest extends KernelTestCase
{
    const TEST_ID = "TEST_ID";

    /**
     * @var RegistryBase
     */
    protected $sut;

    /**
     * @var UUIDGenerator
     */
    protected $uuidGenerator;

    protected function setUp()
    {
        self::bootKernel();
        $this->sut = $this->getSut();
        $this->uuidGenerator = new TestUUID($this->getId());
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->truncateDb();
    }

    protected function truncateDb()
    {
        $this->sut->truncateDb();
    }

    public function test_saveOrUpdate_nonExistingEntity_insertsTheEntity()
    {
        $retrievedEntity = $this->sut->findOneById($this->getId());
        //GUARD ASSERTION
        $this->assertNull($retrievedEntity);
        $entityToPersist = $this->getEntity();
        $persistedEntity = $this->persistAndRetrieveEntity($entityToPersist);
        $this->checkPersistedVersusActual($entityToPersist, $persistedEntity);
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $entityToPersist = $this->getEntity();
        $persistedEntity = $this->persistAndRetrieveEntity($entityToPersist);
        //GUARD ASSERTION
        $this->checkPersistedVersusActual($entityToPersist, $persistedEntity);
        $this->updateEntity($persistedEntity);
        $updatedEntity = $this->persistAndRetrievePassedEntity($persistedEntity);
        $this->assertEquals($persistedEntity, $updatedEntity);
        $this->assertNotEquals($entityToPersist, $updatedEntity);
    }

    public function test_delete_existingEntity_deletesTheEntity()
    {
        $persistedEntity = $this->persistAndRetrieveEntity($this->getEntity());
        //GUARD ASSERTION
        $this->assertNotNull($persistedEntity);
        $this->sut->delete($persistedEntity);
        $deletedEntity = $this->sut->findOneById($this->getId());
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

    protected abstract function getEntities();

    protected abstract function getEntity();

    protected abstract function updateEntity($entity);

    protected abstract function getSut() : RegistryBase;

    protected function getId() {
        return self::TEST_ID;
    }

    protected function persistRecords(array $records)
    {
        foreach($records as $record) {
            $this->sut->saveOrUpdate($record);
        }
    }

    private function persistAndRetrieveEntity($entity)
    {
        return $this->persistAndRetrievePassedEntity($entity);
    }

    protected function persistAndRetrievePassedEntity($entity)
    {
        $this->sut->saveOrUpdate($entity);
        return $this->sut->findOneById($this->getId());
    }

    /**
     * @param $entityToPersist
     * @param $persistedEntity
     */
    protected function checkPersistedVersusActual($entityToPersist, $persistedEntity)
    {
        $this->assertEquals($entityToPersist, $persistedEntity);
    }

    /**
     * @param $entities
     * @param $actual
     */
    protected function countRegistries($entities, $actual)
    {
        $this->assertEquals(count($entities), count($actual));
    }
}