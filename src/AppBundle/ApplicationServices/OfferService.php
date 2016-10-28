<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 27/09/16
 * Time: 22:16
 */

namespace AppBundle\ApplicationServices;


use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\DealerBackgroundImageDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\OfferMailerDomainService;
use AppBundle\DomainServices\OfferMessageDomainService;
use AppBundle\Utils\GoogleMapsAccessor;

class OfferService
{
    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;

    /**
     * @var OfferMessageDomainService
     */
    private $offerMessageDomainService;

    /**
     * @var OfferMailerDomainService
     */
    private $offerMailerDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;

    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;

    private $backgroundDomainService;

    public function __construct(ApplianceOfferDomainService $applianceOfferDomainService, OfferMessageDomainService $offerMessageDomainService, OfferMailerDomainService $offerMailerDomainService, DealerDomainService $dealerDomainService, AvatarDomainService $avatarDomainService, CarApplianceDomainService $carApplianceDomainService, DealerBackgroundImageDomainService $backgroundImageDomainService, GoogleMapsAccessor $googleMapsAccessor)
    {
        $this->applianceOfferDomainService = $applianceOfferDomainService;
        $this->offerMessageDomainService = $offerMessageDomainService;
        $this->offerMailerDomainService = $offerMailerDomainService;
        $this->dealerDomainService = $dealerDomainService;
        $this->avatarDomainService = $avatarDomainService;
        $this->carApplianceDomainService = $carApplianceDomainService;
        $this->googleMapsAccessor = $googleMapsAccessor;
        $this->backgroundDomainService = $backgroundImageDomainService;
    }

    public function makeAnOffer(string $dealerId, string $opportunityId, float $cashPrize, $foundedPrize, bool $inStock, string $message = null)
    {
        $dto = $this->applianceOfferDomainService->makeAnOffer($dealerId, $opportunityId, $cashPrize, $foundedPrize, $inStock);
        if($message) {
            $this->offerMessageDomainService->addDealerMessage($opportunityId, $dealerId, $message);
        }
        $this->offerMailerDomainService->send($opportunityId, $dto->clientEmail, $dto->clientName, $dto->dealerName, $dto->brand, $dto->model, $message);
    }

    public function getOfferDetailForOffer(string $offerId)
    {
        $applianceOffer = $this->applianceOfferDomainService->findApplianceOfferById($offerId);
        $carAppliance = $this->carApplianceDomainService->getApplianceById($applianceOffer->getApplianceId());
        $dealer = $this->dealerDomainService->getDealerById($applianceOffer->getDealerId());
        $background = $this->backgroundDomainService->getBackgroundImageByDealerId($applianceOffer->getDealerId());
        $avatar = $this->avatarDomainService->getAvatarByDealerId($applianceOffer->getDealerId());
        $dealerDto = $dealer->toDTO();
        $position = $dealer->getPosition();
        if ($position) {
            $dealerDto->setPosition($position);
        }
        $applianceOfferDTO = $applianceOffer->toDTO();
        $carApplianceDTO = $carAppliance->toDetailDTO();
        $applianceOfferDTO->financePrice = $applianceOfferDTO->foundedPrice;
        $applianceOfferDTO->appliance = array(
            "color" => array(
                "displayPrice" => 0,
                "optionName" => $carApplianceDTO->color ? $carApplianceDTO->color["name"] : "",
                "price" => 0
            ),
            "extras" => array(),
            "price" => $carApplianceDTO->totalPrice,
            "vehicle" => array(
                "derivative" => $carApplianceDTO->derivative,
                "derivativeToDisplay" => $carApplianceDTO->derivative,
                "fuelType" => $carApplianceDTO->motorType,
                "fuelTypeToDisplay" => $carApplianceDTO->motorType,
                "makeKey" => $carApplianceDTO->brand,
                "makeNameToDisplay" => $carApplianceDTO->brand,
                "modelKey" => $carApplianceDTO->model,
                "modelNameToDisplay" => $carApplianceDTO->model,
                "numberOfDoorsToDisplay" => $carApplianceDTO->numberOfDoors,
                "price" => $carApplianceDTO->price,
                "priceToDisplay" => $carApplianceDTO->price,
                "transmission" => $carApplianceDTO->transmission
            )
        );
        foreach ($carApplianceDTO->extras as $extra) {
            array_push($applianceOfferDTO->appliance["extras"], array(
                "displayPrice" => $extra["price"],
                "optionName" => $extra ? $extra["name"] : "",
                "price" => $extra["price"]
            ));
        }

        if ($avatar) {
            $dealerDto->setAvatar($avatar->toDTO());
        }

        if ($background) {
            $dealerDto->setBackground($background->toDTO());
        }

        $reviews = $this->dealerDomainService->getReviewsByDealer($applianceOffer->getDealerId());
        return array(
            "dealer" => array("profile" => $dealerDto),
            "offers" => array($applianceOfferDTO),
            "reviews" => $reviews
        );
    }

    public function getOffersForAppliance(string $applianceId, $client)
    {
        $carAppliance = $this->carApplianceDomainService->getApplianceById($applianceId);
        $applianceOffers = $this->applianceOfferDomainService->findAllOffersForAppliance($applianceId);
        $dealerIds = array();
        foreach ($applianceOffers as $applianceOffer) {
            array_push($dealerIds, $applianceOffer->getDealerId());
        }
        $dealers = $this->dealerDomainService->findByIds($dealerIds);
        $avatars = $this->avatarDomainService->findAllByDealerIds($dealerIds);
        $offers = array();
        $offerBestPriceId = $this->getOfferBestPrice($applianceOffers);
        $nearestPlace = $this->getOfferDealerNearest($dealers, $client);
        $nearestDealerIds = $nearestPlace['nearestDealerIds'];
        $distances = $nearestPlace['distances'];
        $applianceOffers = $this->sortOffers($applianceOffers);
        foreach ($applianceOffers as $key => $applianceOffer) {
            $bestPrice = false;
            $closest = false;
            $highRating = false;
            $distance = isset($distances[$applianceOffer->getDealerId()]) ? round($distances[$applianceOffer->getDealerId()]) : -1;
            if (in_array($applianceOffer->getId(), $offerBestPriceId))
                $bestPrice = true;

            if (in_array($applianceOffer->getDealerId(), $nearestDealerIds))
                $closest = true;

            $applianceOfferDTO = $applianceOffer->toDTO($bestPrice, $closest, $highRating, $distance, $this->dealerDomainService->getReviewsByDealer($applianceOffer->getDealerId()));
            array_push($offers, $applianceOfferDTO);
            if ($dealers) {
                foreach ($dealers as $dealer) {
                    $dealerDTO = $dealer->toDTO();
                    if ($applianceOfferDTO->dealerId == $dealer->getId()) {
                        $applianceOfferDTO->setDealerInfo($dealerDTO);
                    }
                    if ($avatars) {
                        foreach ($avatars as $avatar) {
                            if ($dealer->getId() == $avatar->getDealerId()) {
                                $dealerDTO->setAvatar($avatar->toDTO());
                            }
                        }
                    }
                }
            }
        }
        return array(
            "appliance" => $carAppliance->toDetailDTO(),
            "offers" => $offers
        );
    }

    public function getOfferBestPrice($offers)
    {
        $bestPrice = 0;
        $offersBestPriceId = [];
        foreach ($offers as $offer) {
            $lowerPrice = $offer->getCashPrice() <= $offer->getFoundedPrice() ? $offer->getCashPrice() : $offer->getFoundedPrice();
            if ($lowerPrice) {
                if ($offersBestPriceId == []) {
                    $bestPrice = $lowerPrice;
                    array_push($offersBestPriceId, $offer->getId());
                } else {
                    if ($bestPrice > $lowerPrice) {
                        $bestPrice = $lowerPrice;
                        $offersBestPriceId = [];
                        array_push($offersBestPriceId, $offer->getId());
                    } else if ($bestPrice == $lowerPrice) {
                        array_push($offersBestPriceId, $offer->getId());
                    }
                }
            }
        }
        return $offersBestPriceId;
    }

    public function getOfferDealerNearest($dealers, $client)
    {
        $nearestDealerIds = [];
        $distances = [];
        if ($client->getPosition()) {
            $pointClient = $client->getPosition();
            $latClient = $pointClient->getLatitude();
            $lngClient = $pointClient->getLongitude();
            $minDistance = 0;
            if ($dealers) {
                foreach ($dealers as $dealer) {
                    $pointDealer = $dealer->getPosition();
                    if ($pointDealer) {
                        $latDealer = $pointDealer->getLatitude();
                        $lngDealer = $pointDealer->getLongitude();
                        $distance = $this->googleMapsAccessor->distance($latClient, $lngClient, $latDealer, $lngDealer, "K");
                    } else {
                        $distance = -1;
                    }
                    $distances[$dealer->getId()] = $distance;
                    if ($nearestDealerIds == [] && $distance != null) {
                        $minDistance = $distance;
                        array_push($nearestDealerIds, $dealer->getId());
                    } elseif ($nearestDealerIds != [] && $distance != null) {
                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                            $nearestDealerIds = [];
                            array_push($nearestDealerIds, $dealer->getId());
                        } else if ($distance == $minDistance) {
                            array_push($nearestDealerIds, $dealer->getId());
                        }
                    }
                }
            }
        }
        return array('nearestDealerIds' => $nearestDealerIds, 'distances' => $distances);
    }

    public function sortOffers($offers)
    {
        if (count($offers) > 1) {
            for ($i = 0; $i < count($offers) - 1; $i++) {
                for ($j = $i + 1; $j < count($offers); $j++) {
                    $minPrice1 = $offers[$i]->getCashPrice() < $offers[$i]->getFoundedPrice() ? $offers[$i]->getCashPrice() : $offers[$i]->getFoundedPrice();
                    $minPrice2 = $offers[$j]->getCashPrice() < $offers[$j]->getFoundedPrice() ? $offers[$j]->getCashPrice() : $offers[$j]->getFoundedPrice();
                    if ($minPrice1 > $minPrice2) {
                        $temp = $offers[$i];
                        $offers[$i] = $offers[$j];
                        $offers[$j] = $temp;
                    }

                }
            }
        }
        return $offers;
    }
}