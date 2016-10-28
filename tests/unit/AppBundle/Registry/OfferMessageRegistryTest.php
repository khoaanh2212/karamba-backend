<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 29/08/16
 * Time: 15:15
 */

use AppBundle\Entity\OfferMessage;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class OfferMessageRegistryTest extends BaseRegistryTest
{

    const PSW = "password";

    protected function setUp()
    {
        parent::setUp();
    }

    protected function truncateDb()
    {
        $this->sut->truncateDb();
    }

    protected function getEntities()
    {
        $return = array();
        array_push($return, $this->constructOfferMessage("offer1", "author1", "client", "hello!"));
        array_push($return, $this->constructOfferMessage("offer1", "author2", "dealer", "bye!"));
        return $return;
    }

    protected function getEntity()
    {
        $client = $this->constructOfferMessage("offer1", "author3", "dealer", "huehuehue");
        return $this->sut->saveOrUpdate($client);
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }
    public function test_findBy_existingThread_shouldReturnTread()
    {
        $this->getEntity();
        $thread = $this->sut->findBy(array('offerId' => 'offer1'));
        $this->assertEquals(count($thread), 1);
    }
    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("OfferMessageRegistry");
    }

    /**
     * @return OfferMessage
     */
    private function constructOfferMessage($offerId, $authorId, $authorType, $message)
    {
        return new OfferMessage($offerId, $authorId, $authorType, $message);
    }

    protected function updateEntity($entity)
    {
        // TODO: Implement updateEntity() method.
    }

}