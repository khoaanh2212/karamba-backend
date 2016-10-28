<?php
/**
 * Created by PhpStorm.
 * User: khoaanh
 * Date: 20/10/2016
 * Time: 11:25
 */

use AppBundle\ApplicationServices\ReviewService;
use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\GiftDomainService;
use AppBundle\DomainServices\ReviewDomainService;
use AppBundle\DomainServices\ReviewDetailDomainService;
use AppBundle\DomainServices\ReviewMailerDomainService;
use AppBundle\Entity\Client;
use AppBundle\Entity\Gift;
use AppBundle\Entity\Review;
use AppBundle\Utils\Point;

class ReviewServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = 'DEALER_ID';
    const CLIENT_ID = 'CLIENT_ID';
    const GIFT_ID = 'GIFT_ID';
    const REVIEWER_FULL_NAME = 'REVIEWER_FULL_NAME';
    const REVIEWER_BUSINESS_NAME = 'REVIEWER_BUSINESS_NAME';
    const COMMENT = 'COMMENT';
    const RATINGS = Array([]);
    /**
     * @var ReviewService
     */
    private $sut;

    /**
     * @var ReviewDomainService
     */
    private $reviewDomainService;

    /**
     * @var ReviewDetailDomainService
     */
    private $reviewDetailDomainService;

    /**
     * @var ClientDomainService
     */
    private $clientDomainService;

    /**
     * @var ReviewMailerDomainService
     */
    private $reviewMailerDomainService;

    /**
     * @var GiftDomainService
     */
    private $giftDomainService;

    protected function setUp()
    {
        $this->giftDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\GiftDomainService")->disableOriginalConstructor()->getMock();
        $this->giftDomainService->expects($this->any())->method("findGiftById")->will($this->returnValue(new Gift("value", "name")));
        $this->clientDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ClientDomainService")->disableOriginalConstructor()->getMock();
        $this->clientDomainService->expects($this->any())
                ->method("findById")
                ->will($this->returnValue(new Client("name", "email", "code", "city", "password", new Point(1,1))));
        $this->reviewMailerDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ReviewMailerDomainService")->disableOriginalConstructor()->getMock();
        $this->reviewDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ReviewDomainService")
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->reviewDetailDomainService = $this->getMockBuilder("AppBundle\\DomainServices\\ReviewDetailDomainService")
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->sut = new ReviewService($this->reviewDomainService, $this->reviewDetailDomainService, $this->clientDomainService, $this->giftDomainService, $this->reviewMailerDomainService);
    }

    public function test_createReview_Should_Call_To_create_ReviewDomainService()
    {
        $this->reviewDomainService->expects($this->once())
            ->method("createReview")
            ->with(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT)
            ->will($this->returnValue($this->getReview()));
        $this->exerciseCreateReview();
    }

    private function getReview() {
        return new Review(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT);
    }

    private function exerciseCreateReview()
    {
        $this->sut->createReview(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT, self::RATINGS);
    }
}
