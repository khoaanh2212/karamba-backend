<?php

namespace AppBundle\DTO;


use Symfony\Component\Validator\Constraints\DateTime;

class ApplianceOfferDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $dealerId;
    /**
     * @var string
     */
    public $applianceId;
    /**
     * @var string
     */
    public $state;
    /**
     * @var float
     */
    public $cashPrice;
    /**
     * @var float
     */
    public $foundedPrice;
    /**
     * @var bool
     */
    public $inStock;
    /**
     * @var bool
     */
    public $isRead;

    /**
     * @var DealerDTO
     */
    public $dealerInfo;

    /**
     * @var bool
     */
    public $isBestPrice;

    /**
     * @var bool
     */
    public $isClosest;

    /**
     * @var bool
     */
    public $isHighestRating;

    /**
     * @var float
     */
    public $distance;

    /**
     * @var string
     */
    public $created;

    public $ratings;

    public function __construct(string $id, string $dealerId, string $applianceId, string $state, string $cashPrice = null, string $foundedPrice = null, bool $inStock = null, bool $isRead = null, $created = null, bool $isBestPrice = null, bool $isClosest = null, bool $isHighestRating = null, float $distance = -1, $ratings = array())
    {
        $this->id = $id;
        $this->dealerId = $dealerId;
        $this->applianceId = $applianceId;
        $this->state = $state;
        $this->cashPrice = $cashPrice;
        $this->foundedPrice = $foundedPrice;
        $this->inStock = $inStock;
        $this->isRead = $isRead;
        $this->isBestPrice = $isBestPrice;
        $this->isClosest = $isClosest;
        $this->isHighestRating;
        $this->distance = $distance;
        if ($created) {
            $this->created = $created->format('Y-m-d H:i:s');
        }
        $this->ratings = $ratings;
    }

    public function setDealerInfo(DealerDTO $info)
    {
        $this->dealerInfo = $info;
    }
}