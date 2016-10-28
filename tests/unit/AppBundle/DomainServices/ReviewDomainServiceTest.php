<?php
/**
 * Created by PhpStorm.
 * User: ka
 * Date: 20/10/2016
 * Time: 11:39
 */

use AppBundle\DomainServices\ReviewDomainService;
use AppBundle\Registry\ReviewRegistry;
use AppBundle\Entity\Review;

class ReviewDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const TEST_ID = 'TEST_ID';
    const DEALER_ID = 'DEALER_ID';
    const CLIENT_ID = 'CLIENT_ID';
    const GIFT_ID = 'GIFT_ID';
    const REVIEWER_FULL_NAME = 'REVIEWER_FULL_NAME';
    const REVIEWER_BUSINESS_NAME = 'REVIEWER_BUSINESS_NAME';
    const COMMENT = 'COMMENT';

    /**
     * @var ReviewDomainService
     */
    private $sut;

    /**
     * @var ReviewRegistry
     */
    private $reviewRegistry;

    protected function setUp()
    {
        $this->reviewRegistry = $this->getMockBuilder("AppBundle\\Registry\\ReviewRegistry")
            ->disableOriginalConstructor()
            ->setMethods(array("findOneById", "saveOrUpdate"))
            ->getMock();

        $this->sut = new ReviewDomainService($this->reviewRegistry);
    }

    public function test_createReview_Should_Call_ReviewRegistry_saveOrUpdate()
    {
        $this->reviewRegistry->expects($this->once())
            ->method("saveOrUpdate")
            ->will($this->returnValue($this->getReview()));
        $this->exerciseCreateReview();
    }

    public function test_acceptReview_calledWithId_callToRepository_findOneById()
    {
        $this->reviewRegistry->expects($this->once())
            ->method("findOneById")
            ->with(self::TEST_ID)
            ->will($this->returnValue($this->getReview()));
        $this->exerciseAcceptReview();
    }

    public function test_acceptReview_calledWithId_reviewExists_callToReviewAccept()
    {
        $pendingReview = $this->getMockBuilder("AppBundle\\Entity\\Review")->disableOriginalConstructor()->getMock();
        $this->reviewRegistry->expects($this->any())
            ->method("findOneById")
            ->with(self::TEST_ID)
            ->will($this->returnValue($pendingReview));
        $acceptedReview = $this->getAcceptedReview();
        $pendingReview->expects($this->once())->method("accept")->will($this->returnValue($acceptedReview));
        $this->exerciseAcceptReview();
    }

    public function test_rejectReview_calledWithId_callToRepository_findOneById()
    {
        $this->reviewRegistry->expects($this->once())
            ->method("findOneById")
            ->with(self::TEST_ID)
            ->will($this->returnValue($this->getReview()));
        $this->exerciseRejectReview();
    }

    public function test_rejectReview_calledWithId_reviewExists_callToReviewReject()
    {
        $pendingReview = $this->getMockBuilder("AppBundle\\Entity\\Review")->disableOriginalConstructor()->getMock();
        $this->reviewRegistry->expects($this->any())
            ->method("findOneById")
            ->with(self::TEST_ID)
            ->will($this->returnValue($pendingReview));
        $rejectedReview = $this->getRejectedReview();
        $pendingReview->expects($this->once())->method("reject")->will($this->returnValue($rejectedReview));
        $this->exerciseRejectReview();
    }

    /**
     * @return Review
     */
    private function exerciseCreateReview()
    {
        return $this->sut->createReview(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT);
    }

    private function exerciseAcceptReview()
    {
        $this->sut->acceptReview(self::TEST_ID);
    }

    private function exerciseRejectReview()
    {
        $this->sut->rejectReview(self::TEST_ID);
    }

    private function getAcceptedReview()
    {
        $pendingReview = new Review(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT);
        $pendingReview->accept();
        return $pendingReview;
    }

    private function getRejectedReview()
    {
        $pendingReview = new Review(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT);
        $pendingReview->reject();
        return $pendingReview;
    }

    private function getReview(): Review
    {
        return new Review(self::DEALER_ID, self::CLIENT_ID, self::GIFT_ID, self::REVIEWER_FULL_NAME, self::REVIEWER_BUSINESS_NAME, self::COMMENT);
    }
}