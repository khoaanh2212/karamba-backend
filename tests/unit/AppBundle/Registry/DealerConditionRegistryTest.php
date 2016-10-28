<?php


use AppBundle\Entity\DealerCondition;


require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class DealerConditionRegistryTest extends BaseRegistryTest
{

    protected function truncateDb()
    {
        $this->sut->truncateDb();
        $this->sut->saveOrUpdate(new DealerCondition("FREE_DELIVERY_100"));
        $this->sut->saveOrUpdate(new DealerCondition("FULL_GAS"));
        $this->sut->saveOrUpdate(new DealerCondition("FREE_TRANSPORT"));
    }

    public function test_findAllByIds_willReturn_conditions()
    {
        $actual = $this->sut->findAllByIds(array(1, 3));
        $this->assertEquals("{\"id\":1,\"text\":\"FREE_DELIVERY_100\"}-{\"id\":3,\"text\":\"FREE_TRANSPORT\"}", json_encode($actual[0]->toDTO())."-".json_encode($actual[1]->toDTO()));
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
        $this->assertEquals(3, count($actual));
    }

    protected function getEntity()
    {
        return $this->constructCondition();
    }

    protected function updateEntity($entity)
    {
        $entity->setConditionName("ANOTHER CONDITION NAME");
    }

    protected function getId() {
        return 4;
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("DealerConditionRegistry");
    }

    /**
     * @return DealerCondition
     */
    private function constructCondition()
    {
        $dealerCondition = new DealerCondition("A CONDITION NAME");
        return $dealerCondition;
    }
}