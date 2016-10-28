<?php


use AppBundle\Entity\Gift;


require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class GiftRegistryTest extends BaseRegistryTest
{

    protected function truncateDb()
    {
        $this->sut->truncateDb();
        $this->sut->saveOrUpdate(new Gift("20 €", "Deliberry"));
        $this->sut->saveOrUpdate(new Gift("10 €", "Hailo"));
        $this->sut->saveOrUpdate(new Gift("10 €", "Glovo"));
        $this->sut->saveOrUpdate(new Gift("10 €", "MyTaxi"));
    }

    public function test_findAll_returnAllPersistedEntities()
    {
        $entities = $this->getEntities();
        $this->persistRecords($entities);
        $actual = $this->sut->findAll();
        $this->countRegistries($entities, $actual);
    }

    /**
     * @param $entityToPersist
     * @param $persistedEntity
     */
    protected function checkPersistedVersusActual($entityToPersist, $persistedEntity)
    {
        $entityToPersist->setId($this->getId());
        $this->assertEquals($entityToPersist, $persistedEntity);
    }

    protected function getEntities()
    {
        $result = array();
        return $result;
    }

    /**
     * @param $entities
     * @param $actual
     */
    protected function countRegistries($entities, $actual)
    {
        $this->assertEquals(4, count($actual));
    }

    protected function getEntity()
    {
        return $this->constructCondition();
    }

    protected function updateEntity($entity)
    {
        $entity->setGiftName("ANOTHER GIFT NAME");
    }

    protected function getId() {
        return 5;
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("GiftRegistry");
    }

    /**
     * @return Gift
     */
    private function constructCondition()
    {
        return new Gift("10 €", "MyTaxi");
    }
}