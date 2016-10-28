<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 29/08/16
 * Time: 16:10
 */

use AppBundle\Entity\OfferMessage;
use Symfony\Component\HttpFoundation\Session\Session;


class OfferMessageTest extends PHPUnit_Framework_TestCase
{
    const MESSAGE = "test";

    /**
     * @var OfferMessage
     */
    private $sut;


    protected function setUp()
    {
        $this->sut = new OfferMessage("offerId", "authorId", "dealer", self::MESSAGE);
    }

    public function test_validate_entityIsBuild()
    {
        $this->assertEquals($this->sut->getMessage(), self::MESSAGE);
    }
    
}