<?php

namespace AppBundle\DomainServices;

use Doctrine\ORM\EntityNotFoundException;
use AppBundle\Entity\Review;
use AppBundle\Registry\ReviewRegistry;

class ReviewDomainService
{
    /**
     * @var ReviewRegistry
     */
    private $registry;

    public function __construct(ReviewRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getReviewsByDealer(string $dealerId)
    {
        return $this->registry->findBy(array('dealerId' => $dealerId), array('created' => 'ASC'));
    }

    public function createReview(string $dealerId, string $clientId, string $giftId, string $reviewerFullName,
                                 string $reviewerBusinessName, string $comment)
    {
        $review = new Review($dealerId, $clientId, $giftId, $reviewerFullName, $reviewerBusinessName, $comment);
        return $this->registry->saveOrUpdate($review);
    }

    public function rejectReview(string $id)
    {
        $pendingReview = $this->registry->findOneById($id);
        if(!$pendingReview) {
            throw new EntityNotFoundException("unexisting application[".$id."]");
        }
        $pendingReview->reject();
        return $this->registry->saveOrUpdate($pendingReview);
    }

    public function acceptReview(string $id)
    {
        $pendingReview = $this->registry->findOneById($id);
        if(!$pendingReview) {
            throw new EntityNotFoundException("unexisting application[".$id."]");
        }
        $pendingReview->accept();
        return $this->registry->saveOrUpdate($pendingReview);
    }
}