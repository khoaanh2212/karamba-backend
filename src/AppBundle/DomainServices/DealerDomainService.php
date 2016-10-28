<?php

namespace AppBundle\DomainServices;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\Review;
use AppBundle\Registry\DealerConditionsRegistry;
use AppBundle\Registry\DealerRegistry;
use AppBundle\Registry\ReviewRegistry;
use AppBundle\Registry\ReviewDetailRegistry;
use AppBundle\Utils\Point;
use Doctrine\ORM\EntityNotFoundException;
use AppBundle\Utils\ReviewType;
use Symfony\Component\Validator\Constraints\DateTime;

class DealerDomainService
{
    /**
     * @var DealerRegistry
     */
    private $registry;

    /**
     * @var DealerConditionsRegistry
     */
    private $dealerConditionRegistry;

    private $reviewRegistry;

    private $reviewDetailRegistry;

    public function __construct(DealerRegistry $registry, DealerConditionsRegistry $dealerConditionRegistry, ReviewRegistry $reviewRegistry, ReviewDetailRegistry $reviewDetailRegistry)
    {
        $this->registry = $registry;
        $this->dealerConditionRegistry = $dealerConditionRegistry;
        $this->reviewRegistry = $reviewRegistry;
        $this->reviewDetailRegistry = $reviewDetailRegistry;
    }

    public function createDealer(string $name, string $phoneNumber, string $vendorName, string $vendorRole, string $email, string $password): Dealer
    {
        $dealer = new Dealer($name, $phoneNumber, $vendorName, $vendorRole, $email, $password);
        return $this->registry->saveOrUpdate($dealer);
    }

    public function updateDealer(string $dealerId, string $name = null, string $description = null, string $phoneNumber = null, string $vendorName = null, string $vendorRole = null, string $password = null, string $address = null, string $scheduling = null, string $deliveryConditions = null, string $specialConditions = null, array $generalConditionsIds = null, string $zipCode = null, Point $position = null)
    {
        $dealer = $this->retrieveDealer($dealerId);
        if($name){
            $dealer->setName($name);
        }
        if($description) {
            $dealer->setDescription($description);
        }
        if($phoneNumber) {
            $dealer->setPhoneNumber($phoneNumber);
        }
        if($vendorName) {
            $dealer->setVendorName($vendorName);
        }
        if($vendorRole) {
            $dealer->setVendorRole($vendorRole);
        }
        if($password) {
            $dealer->setPassword($password);
        }
        if($address) {
            $dealer->setAddress($address);
        }
        if($scheduling) {
            $dealer->setSchedule($scheduling);
        }
        if($deliveryConditions) {
            $dealer->setDeliveryConditions($deliveryConditions);
        }
        if($specialConditions) {
            $dealer->setSpecialConditions($specialConditions);
        }
        if($zipCode) {
            $dealer->setZipCode($zipCode);
        }
        if($position) {
            $dealer->setPosition($position);
        }
        $dealer->setUpdated();
        if (is_array($generalConditionsIds)) {
            $dealer->clearGeneralConditions();
            $conditions = $this->dealerConditionRegistry->findAllByIds($generalConditionsIds);
            if ($conditions) {
                foreach ($conditions as $condition) {
                    $dealer->addGeneralCondition($condition);
                }
            } else {
                $dealer->clearGeneralConditions();
            }
        }
        $dealer = $this->registry->saveOrUpdate($dealer);
        return $dealer;
    }

    public function getDealerById(string $id): Dealer
    {
        return $this->registry->findOneById($id);
    }

    /**
     * @param string $dealerId
     * @throws EntityNotFoundException
     * @return Dealer
     */
    private function retrieveDealer(string $dealerId)
    {
        $dealer = $this->registry->findOneById($dealerId);
        if (!$dealer) {
            throw new EntityNotFoundException();
        }
        return $dealer;
    }

    public function findDealerIdsByModelInPosition(string $brand, string $model, Point $topWestSquareEnvelope, Point $lowEastSquareEnvelope)
    {
        return $this->registry->findDealerIdsByModelInPosition($brand, $model, $topWestSquareEnvelope, $lowEastSquareEnvelope);
    }

    public function findDealerIdsByModel(string $brand, string $model)
    {
        return $this->registry->findDealerIdsByModel($brand, $model);
    }

    public function findByIds($dealerIds)
    {
        return $this->registry->findByIds($dealerIds);
    }

    public function getReviewsByDealer(string $dealerId)
    {
        $reviews = $this->reviewRegistry->findBy(array('dealerId' => $dealerId), array('created' => 'ASC'));
        $reviewDTOs = array();
        foreach($reviews as $review){
            $reviewDTO = $review->toDTO();
            $reviewDetails = $this->reviewDetailRegistry->findBy(array('reviewId' => $review->getId()));
            $reviewDTO->setReviewDetails($reviewDetails);
            array_push($reviewDTOs, $reviewDTO);
        }
        return array(
            "ratings" => $this->calculateAverageRatings($reviewDTOs),
            "ratingsByType" => $this->calculateAverageRatingsType($reviewDTOs),
            "comments" => array("data" => $reviewDTOs)
        );
    }

    private function countNumberOfReviewsByRating($reviewDTOs, $rating) {
        $numberOfReviews = 0;
        foreach ($reviewDTOs as $reviewDTO) {
            if (round($reviewDTO->rating, 0) == $rating) {
                $numberOfReviews++;
            }
        }
        return $numberOfReviews;
    }

    private function calculateAverageRatings($reviewDTOs) {
        $ratings = array(
            "5" => $this->countNumberOfReviewsByRating($reviewDTOs, 5),
            "4" => $this->countNumberOfReviewsByRating($reviewDTOs, 4),
            "3" => $this->countNumberOfReviewsByRating($reviewDTOs, 3),
            "2" => $this->countNumberOfReviewsByRating($reviewDTOs, 2),
            "1" => $this->countNumberOfReviewsByRating($reviewDTOs, 1)
        );
        return $ratings;
    }

    private function calculateAverageRatingByType($reviewDTOs, string $reviewType) {
        $reviewsByType = array();
        foreach ($reviewDTOs as $reviewDTO) {
            $reviewDetails = $reviewDTO->getReviewDetails();
            foreach ($reviewDetails as $reviewDetail) {
                if ($reviewDetail->getType() == $reviewType) {
                    array_push($reviewsByType, $reviewDetail);
                }
            }
        }
        return $this->calculateRating($reviewsByType);
    }

    private function calculateAverageRatingsType($reviewDTOs) {
        $ratingsByType = array(
            ReviewType::KINDNESS => $this->calculateAverageRatingByType($reviewDTOs, ReviewType::KINDNESS),
            ReviewType::KNOWLEDGE => $this->calculateAverageRatingByType($reviewDTOs, ReviewType::KNOWLEDGE),
            ReviewType::COMMUNICATION => $this->calculateAverageRatingByType($reviewDTOs, ReviewType::COMMUNICATION),
            ReviewType::PROCESS => $this->calculateAverageRatingByType($reviewDTOs, ReviewType::PROCESS)
        );
        return $ratingsByType;
    }

    private function calculateRating($reviewDetails) {
        if (count($reviewDetails) == 0){
            return 0;
        }
        $totalRating = 0;
        foreach ($reviewDetails as $detail) {
            $totalRating += $detail->getRating();
        }
        return $totalRating / count($reviewDetails);
    }
}