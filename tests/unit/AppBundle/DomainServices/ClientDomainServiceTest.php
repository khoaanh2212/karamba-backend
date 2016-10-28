<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 17:01
 */

use AppBundle\DomainServices\ClientDomainService;
use AppBundle\Entity\Client;
use AppBundle\Registry\ClientRegistry;
use AppBundle\Utils\Point;

class ClientDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const NAME = "client";
    const ZIPCODE= "WSA15 854";
    const EMAIL = "email";
    const PASSWORD = "password";
    const ID = 1;
    const CITY = "city";

    /**
     * @var ClientDomainService
     */
    private $sut;

    /**
     * @var ClientRegistry
     */
    private $registry;

    /**
     * @var Client
     */
    private $dummyClient;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\ClientRegistry")->disableOriginalConstructor()->setMethods(
            array("saveOrUpdate", "findOneById")
        )->getMock();

        $this->dummyClient = $this->getMockBuilder("AppBundle\\Entity\\Client")->disableOriginalConstructor()->getMock();
        $this->sut = new ClientDomainService($this->registry);
    }

    public function test_create_withData_willCallToRegistrySaveWithCreatedClient()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate")->will($this->returnValue($this->getClient()));
        $this->exerciseCreateClient();
    }

    public function test_create_withData_willReturnThePersistedClient()
    {
        $client = $this->configureRegistrySaveOrUpdateAsStub($this->getClient());
        $actual = $this->exerciseCreateClient();
        $this->assertEquals($client, $actual);
    }

    public function test_findById_call_registryFindByIdWithTheId()
    {
        $this->registry->expects($this->once())->method("findOneById")->with(self::ID)->will($this->returnValue($this->getClient()));
        $this->sut->findById(self::ID);
    }

    public function test_findById_returnEntityReturnedByTheRegistry()
    {
        $client = $this->getClient();
        $this->registry->expects($this->any())->method("findOneById")->with(self::ID)->will($this->returnValue($client));
        $actual = $this->sut->findById(self::ID);
        $this->assertEquals($client, $actual);
    }
    

    private function getClient(): Client
    {
        return new Client(self::NAME, self::EMAIL, self::ZIPCODE, self::CITY, self::PASSWORD, new Point());
    }

    /**
     * @return Client
     */
    private function exerciseCreateClient()
    {
        return $this->sut->create(self::NAME, self::EMAIL, self::ZIPCODE, self::CITY, self::PASSWORD, new Point());
    }

    private function configureRegistrySaveOrUpdateAsStub(Client $client = null)
    {
        $this->registry->expects($this->any())->method("saveOrUpdate")->will($this->returnValue($client));
        return $client;
    }
    
}