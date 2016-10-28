<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 31/08/16
 * Time: 16:59
 */

use AppBundle\DomainServices\OfferMessageDomainService;
use AppBundle\Entity\OfferMessage;
use AppBundle\Registry\OfferMessageRegistry;
use AppBundle\Utils\UUIDGenerator;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__.'/../../utils/TestUUID.php';

class OfferMessageDomainServiceTest extends PHPUnit_Framework_TestCase
{

    const OFFER_ID = "offerId";
    const AUTHOR_ID = "authorId";
    const MESSAGE = "test";

    /**
     * @var OfferMessageDomainService
     */
    private $sut;

    /**
     * @var OfferMessageRegistry
     */
    private $registry;

    /**
     * @var OfferMessage
     */
    private $dummyMessage;

    /**
     * @var UUIDGenerator
     */
    protected $uuidGenerator;

    protected function setUp()
    {
        $this->uuidGenerator = new TestUUID("TESTID");
        UUIDGeneratorFactory::setInstance($this->uuidGenerator);
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\OfferMessageRegistry")->disableOriginalConstructor()->setMethods(
            array("saveOrUpdate", "findBy")
        )->getMock();

        $this->dummyMessage= $this->getMockBuilder("AppBundle\\Entity\\OfferMessage")->disableOriginalConstructor()->getMock();
        $this->sut = new OfferMessageDomainService($this->registry);
    }

    public function test_addDealerMessage_willReturnMessage()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($this->getMessage("dealer"))->will($this->returnValue($this->getMessage("dealer")));
        $this->sut->addDealerMessage(self::OFFER_ID, self::AUTHOR_ID, self::MESSAGE);
    }
    
    public function test_addClientMessage_willReturnMessage()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate")->with($this->getMessage("client"))->will($this->returnValue($this->getMessage("client")));
        $this->sut->addClientMessage(self::OFFER_ID, self::AUTHOR_ID, self::MESSAGE);
    }
    public function test_getThreadByOffer_willArray()
    {
        $this->registry->expects($this->once())->method("findBy")->with(array('offerId' => self::OFFER_ID))->will($this->returnValue(array($this->getMessage("client"),$this->getMessage("dealer"))));
        $actual = $this->sut->getThreadByOffer(self::OFFER_ID);
        $this->assertEquals(count($actual), 2);
    }
    private function getMessage($authorType): OfferMessage
    {
        return new OfferMessage(self::OFFER_ID, self::AUTHOR_ID, $authorType, self::MESSAGE);
    }

}
