<?php

use AppBundle\Entity\Administrator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use AppBundle\Utils\SessionStorage;
use AppBundle\Utils\SessionStorageFactory;
use AppBundle\Utils\UUIDGenerator;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../utils/TestUUID.php';

class AdministratorTest extends PHPUnit_Framework_TestCase
{
    const UUID = "TESTID";
    const ANOTHERID = "ANOTHERID";
    /**
     * @var string
     */
    const USERNAME = "a user";

    /**
     * @var string
     */
    const PASSWORD = "a password";

    /**
     * @var Administrator
     */
    private $sut;

    /**
     * @var Session
     */
    private $innerStorage;

    /**
     * @var SessionStorage
     */
    private $sessionStorage;

    /**
     * @var UUIDGenerator
     */
    private $fakeIdGenerator;

    protected function setUp()
    {
        $session = new Session(new MockArraySessionStorage());
        $this->innerStorage = $session;
        $this->sessionStorage = new SessionStorage($this->innerStorage);
        $this->fakeIdGenerator = new TestUUID(self::UUID);
        SessionStorageFactory::setInstance($this->sessionStorage);
        UUIDGeneratorFactory::setInstance($this->fakeIdGenerator);
        $this->sut = new Administrator(self::USERNAME, self::PASSWORD);
    }

    public function test_validate_validUserNameAndValidPassword_willReturn_true() {
        $this->assertTrue($this->exerciseValidate());
    }

    public function test_validate_validUserNameAndValidPassword_willSetUUID_toSessionStorage() {
        $this->exerciseValidate();
        $this->assertEquals(self::UUID, $this->innerStorage->get('TOKEN_ID'));
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function test_validate_validUserName_InvalidPassword_raiseException() {
        $this->sut->validate(self::USERNAME, "INVALID PASSWORD");
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function test_validate_invalidUserName_validPassword_raiseException() {
        $this->sut->validate("INVALID USERNAME", self::PASSWORD);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function test_validate_invalidUserName_invalidPassword_raiseException() {
        $this->sut->validate("INVALID USERNAME", "INVALID PASSWORD");
    }

    private function exerciseValidate(): bool {
        return $this->sut->validate(self::USERNAME, self::PASSWORD);
    }
}