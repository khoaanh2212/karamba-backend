<?php

namespace AppBundle\DTO;

class ReviewDetailDTO
{
    public $type;

    public $rating;

    public function __construct(string $type, float $rating) {
        $this->type = type;
        $this->rating = $rating;
    }
}