<?php
/**
 * Created by PhpStorm.
 * User: khoaanh
 * Date: 20/10/2016
 * Time: 11:05
 */

namespace AppBundle\ApplicationServices;

use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\GiftDomainService;
use AppBundle\DomainServices\ReviewDomainService;
use AppBundle\DomainServices\ReviewDetailDomainService;
use AppBundle\DomainServices\ReviewMailerDomainService;
use ProxyManager\Proxy\GhostObjectInterface;

class ReviewService
{
    /**
     * @var ReviewDomainService
     */
    private $reviewDomainService;

    /**
     * @var ReviewDetailDomainService
     */
    private $reviewDetailDomainService;

    /**
     * @var ReviewMailerDomainService
     */
    private $reviewMailer;

    /**
     * @var GiftDomainService
     */
    private $giftDomainService;

    public function __construct(ReviewDomainService $reviewDomainService, ReviewDetailDomainService $reviewDetailDomainService, ClientDomainService $clientDomainService, GiftDomainService $giftDomainService, ReviewMailerDomainService $reviewMailer)
    {
        $this->reviewDomainService = $reviewDomainService;
        $this->reviewDetailDomainService = $reviewDetailDomainService;
        $this->clientDomainService = $clientDomainService;
        $this->giftDomainService = $giftDomainService;
        $this->reviewMailer = $reviewMailer;
    }

    public function createReview(string $dealerId, string $clientId, string $giftId, string $reviewerFullName,
                                 string $reviewerBusinessName, string $comment, $ratings)
    {
        $review = $this->reviewDomainService->createReview($dealerId, $clientId, $giftId, $reviewerFullName, $reviewerBusinessName, $comment);
        $client = $this->clientDomainService->findById($clientId);
        $gift = $this->giftDomainService->findGiftById($giftId);
        $this->reviewDetailDomainService->addDetails($review->getId(), $ratings);
        $this->reviewMailer->send($client->getUsername(), $reviewerFullName, $reviewerBusinessName, $gift->getGiftName());
    }

    public function rejectReview(string $id)
    {
        $review = $this->reviewDomainService->rejectReview($id);
        return $review->getId();
    }

    public function acceptReview(string $id)
    {
        $review = $this->reviewDomainService->acceptReview($id);
        return $review->getId();
    }
}