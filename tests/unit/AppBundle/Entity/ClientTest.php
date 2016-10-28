<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 16:10
 */

use AppBundle\Entity\Client;
use AppBundle\Utils\Point;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Utils\SessionStorage;
use AppBundle\Utils\SessionStorageFactory;
use AppBundle\Utils\UUIDGenerator;
use AppBundle\Utils\UUIDGeneratorFactory;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

require_once __DIR__ . '/../../utils/TestUUID.php';

class ClientTest extends PHPUnit_Framework_TestCase
{
    const EMAIL = "email@email";
    const PASSWORD = "password";
    const ID = "ID";
    const CITY = "CITY";
    /**
     * @var Client
     */
    private $sut;

    private $sessionData;

    /**
     * @var UUIDGenerator
     */
    private $fakeIdGenerator;

    protected function setUp()
    {
        $this->fakeIdGenerator = new TestUUID(self::ID);
        UUIDGeneratorFactory::setInstance($this->fakeIdGenerator);
        $this->sessionData = new Session(new MockArraySessionStorage());
        SessionStorageFactory::setInstance(new SessionStorage($this->sessionData));
        $this->sut = new Client("name", self::EMAIL, "SWA15 451", self::CITY,self::PASSWORD, new Point());
    }

    public function test_validate_withUserName_andPassword_andUserNameAndPasswordAreSame_willReturnTrue()
    {
        $this->assertTrue($this->sut->validate(self::EMAIL, self::PASSWORD));
    }
    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function test_validate_withUserName_andPassword_invalidUserName_throwException()
    {
        $this->sut->validate("invalid username", self::PASSWORD);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function test_validate_withUserName_andPassword_invalidPassword_throwException()
    {
        $this->sut->validate(self::EMAIL, "invalid");
    }

    public function test_validate_withUserName_andPassword_andUserNameAndPasswordAreSame_willAddTheToken()
    {
        $this->assertNull($this->sessionData->get('TOKEN_ID'));
        $this->sut->validate(self::EMAIL, self::PASSWORD);
        $this->assertEquals(self::ID, $this->sessionData->get('TOKEN_ID'));
    }

    public function test_validate_withUserName_andPassword_andUserNameAndPasswordAreSame_willAddTheUserName()
    {
        $this->assertNull($this->sessionData->get('USERNAME'));
        $this->sut->validate(self::EMAIL, self::PASSWORD);
        $this->assertEquals(self::EMAIL, $this->sessionData->get('USERNAME'));
    }

}