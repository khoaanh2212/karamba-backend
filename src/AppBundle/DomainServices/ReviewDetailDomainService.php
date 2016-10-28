<?php

namespace AppBundle\DomainServices;

use AppBundle\Entity\ReviewDetail;
use AppBundle\Registry\ReviewDetailRegistry;

class ReviewDetailDomainService
{
    /**
     * @var ReviewDetailRegistry
     */
    private $registry;

    public function __construct(ReviewDetailRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function addDetails(string $id, $ratings) {
        foreach ($ratings as $rating) {
            $detail = new ReviewDetail($id, $rating->type, $rating->rating);
            $this->registry->saveOrUpdate($detail);
        }
    }

    public function getReviewDetails(string $reviewId)
    {
        return $this->registry->findBy(array('reviewId' => $reviewId));
    }
}