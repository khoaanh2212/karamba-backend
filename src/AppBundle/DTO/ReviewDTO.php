<?php

namespace AppBundle\DTO;

use DateTime;

class ReviewDTO
{
    public $id;

    public $reviewerFullName;

    public $reviewerBusinessName;

    public $comment;

    public $created;

    public $rating;

    public $state;

    private $reviewDetails;

    public function __construct(string $id, string $reviewerFullName, string $reviewerBusinessName, string $comment, DateTime $created, $state) {
        $this->id = $id;
        $this->reviewerFullName = $reviewerFullName;
        $this->reviewerBusinessName = $reviewerBusinessName;
        $this->comment = $comment;
        if ($created) {
            $this->created = $created->format('Y-m-d H:i:s');
        }
        $this->state = $state;
    }

    public function setReviewDetails($reviewDetails) {
        $this->reviewDetails = $reviewDetails;
        $this->rating = $this->calculateRating($reviewDetails);
    }

    public function getReviewDetails() {
        return $this->reviewDetails;
    }

    private function calculateRating($reviewDetails) {
        $totalRating = 0;
        foreach ($reviewDetails as $detail) {
            $totalRating += $detail->getRating();
        }
        return $totalRating / count($reviewDetails);
    }
}