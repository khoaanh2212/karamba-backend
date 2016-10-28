<?php

use AppBundle\Entity\AcceptedDealerApplication;
use AppBundle\Entity\DealerApplication;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGenerator;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/TestUUID.php';

class AcceptedDealerApplicationTest extends PHPUnit_Framework_TestCase
{
    const TEST_ID = "TEST_ID";
    const ANOTHER_ID = "ANOTHER_ID";
    /**
     * @var AcceptedDealerApplication
     */
    private $sut;

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    protected function setUp()
    {
        SystemClock::setInnerDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2001-01-01 10:00:00'));
        $this->uuidGenerator = new TestUUID(self::TEST_ID);
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->sut = DealerApplication::constructAcceptedApplication("vendorname", "dealername", "vendorRole", "phoneNumber", "email", "howArrivedHere", "token");
    }

    public function test_refreshToken_shouldUpdateFields()
    {
        //GUARD ASSERTION
        $this->assertEquals("AppBundle\\Entity\\DealerApplication>>[id]:TEST_ID,[vendorName]:vendorname,[dealerName]:dealername,[vendorRole]:vendorRole,[phoneNumber]:phoneNumber,[email]:email,[howArrived]:howArrivedHere,[token]:TEST_ID,[expiration]:2001-01-08 10:00:00", "".$this->sut);
        SystemClock::setInnerDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2001-01-01 10:00:05'));
        $this->uuidGenerator = new TestUUID(self::ANOTHER_ID);
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->sut->refreshToken();
        $this->assertEquals("AppBundle\Entity\DealerApplication>>[id]:TEST_ID,[vendorName]:vendorname,[dealerName]:dealername,[vendorRole]:vendorRole,[phoneNumber]:phoneNumber,[email]:email,[howArrived]:howArrivedHere,[token]:ANOTHER_ID,[expiration]:2001-01-08 10:00:05", "".$this->sut);
    }

    public function test_isTokenValid_tokenHasNotExpired_shouldReturnTrue()
    {
        SystemClock::setInnerDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2001-01-06 10:00:00'));
        $actual = $this->sut->checkValidToken();
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\NonceExpiredException
     */
    public function test_isTokenInvalid_tokenHasNotExpired_shouldRaiseException()
    {
        SystemClock::setInnerDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2001-01-16 10:00:00'));
        $this->sut->checkValidToken();
    }

    public function test_process_willReturnAnProcessedDealerApplication()
    {
        $actual = $this->sut->process();
        $this->assertEquals("AppBundle\Entity\DealerApplication>>[id]:TEST_ID,[vendorName]:vendorname,[dealerName]:dealername,[vendorRole]:vendorRole,[phoneNumber]:phoneNumber,[email]:email,[howArrived]:howArrivedHere,[token]:,[expiration]:2001-01-08 10:00:00", "".$actual);
    }
}