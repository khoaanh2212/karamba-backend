<?php


use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\PendingDealerApplication;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/TestUUID.php';

class PendingDealerApplicationTest extends PHPUnit_Framework_TestCase
{
    const TEST_ID = "TEST_ID";
    /**
     * @var PendingDealerApplication
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
        $this->sut = DealerApplication::constructPendingApplication("vendor name", "dealer name", "vendor role", "phone", "email", "how");
    }

    public function test_accept_willReturnAnAcceptedDealerApplicationWithUniqueIdentifierAndExpirationDate()
    {
        //GUARD ASSERTION
        $this->assertEquals(self::TEST_ID, $this->sut->getId());
        $actual = $this->sut->accept();
        $this->assertEquals("AppBundle\Entity\DealerApplication>>[id]:TEST_ID,[vendorName]:vendor name,[dealerName]:dealer name,[vendorRole]:vendor role,[phoneNumber]:phone,[email]:email,[howArrived]:how,[token]:TEST_ID,[expiration]:2001-01-08 10:00:00", "".$actual);
    }
    public function test_reject_willReturnAnRejectedDealerApplication()
    {
        //GUARD ASSERTION
        $this->assertEquals(self::TEST_ID, $this->sut->getId());
        $actual = $this->sut->reject();
        $this->assertEquals($actual->_getDiscr(), DealerApplication::REJECTED);
    }
}