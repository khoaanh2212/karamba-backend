<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 16:30
 */

use AppBundle\Entity\Client;
use AppBundle\Entity\Dealer;
use AppBundle\Utils\Point;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class ClientRegistryTest extends BaseRegistryTest
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
        array_push($return, $this->constructClient("client1", "email1"));
        array_push($return, $this->constructClient("client2", "email2"));
        array_push($return, $this->constructClient("client3", "email3"));
        return $return;
    }

    protected function getEntity()
    {
        $client = $this->constructClient("client", "email");
        return $this->sut->saveOrUpdate($client);
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("ClientRegistry");
    }

    /**
     * @return Client
     */
    private function constructClient($name, $email)
    {
        return new Client($name, $email, "08011", "CITY", self::PSW, new Point(11.1, 11.2));
    }

    protected function updateEntity($entity)
    {
        // TODO: Implement updateEntity() method.
    }
    
}