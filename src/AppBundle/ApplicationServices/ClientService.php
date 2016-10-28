<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 17:17
 */

namespace AppBundle\ApplicationServices;

use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\GiftDomainService;
use AppBundle\Utils\GoogleMapsAccessor;


class ClientService
{
    /**
     * @var GiftDomainService
     */
    private $giftDomainService;

    /**
     * @var ClientDomainService
     */
    private $clientDomainService;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;

    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var int
     */
    private $distanceInKm;


    public function __construct(ClientDomainService $clientDomainService, GoogleMapsAccessor $googleMapsAccessor, CarApplianceDomainService $carApplianceDomainService, ApplianceOfferDomainService $applianceOfferDomainService, DealerDomainService $dealerDomainService, GiftDomainService $giftDomainService, int $distanceInKm)
    {
        $this->clientDomainService = $clientDomainService;
        $this->googleMapsAccessor = $googleMapsAccessor;
        $this->carApplianceDomainService = $carApplianceDomainService;
        $this->applianceOfferDomainService = $applianceOfferDomainService;
        $this->dealerDomainService = $dealerDomainService;
        $this->giftDomainService = $giftDomainService;
        $this->distanceInKm = $distanceInKm;
    }

    public function createClientAndAppliance(string $name, string $email, string $zipCode, string $password, int $vehicleId, string $brand, string $model, array $extras, int $package = null, int $color = null)
    {
        $client = $this->createClient($name, $email, $zipCode, $password);
        $appliance = $this->carApplianceDomainService->createAppliance($client->getId(), $vehicleId, $brand, $model, $extras, $package, $color);

        $dealerIds = null;
        if ($this->distanceInKm > 0) {
            $squarePositions = $client->getPosition()->getSquareCoordinates($this->distanceInKm);
            $dealerIds = $this->dealerDomainService->findDealerIdsByModelInPosition($brand, $model, $squarePositions[0], $squarePositions[1]);
        } else {
            $dealerIds = $this->dealerDomainService->findDealerIdsByModel($brand, $model);
        }
        $this->applianceOfferDomainService->createOffersForCarAppliance($dealerIds, $appliance->getId());
    }

    public function getClientGifts()
    {
        $gifts = $this->giftDomainService->findGifts();
        $result = array();
        foreach ($gifts as $gift) {
            array_push($result, $gift->toDTO());
        }
        return $result;
    }

    public function getClientById(string $id)
    {
        $client = $this->clientDomainService->findById($id);
        $clientDTO = $client->toDTO();
        return $clientDTO;
    }

    public function updateClient(string $id, string $name = null, string $zipCode = null, string $password = null)
    {
        $position = null;
        if ($zipCode) {
            $position = $this->googleMapsAccessor->getPositionFromZipCode($zipCode);
        }
        $this->clientDomainService->updateClient($id, $name, $zipCode, $password, $position["position"]);
    }

    public function getRatingOfDealer(string $dealerId)
    {
        $rating = $this->dealerDomainService->getReviewsByDealer($dealerId);
        return array('rating' => $rating);
    }

    private function createClient(string $name, string $email, string $zipCode, string $password)
    {
        $position = $this->googleMapsAccessor->getPositionFromZipCode($zipCode);
        return $this->clientDomainService->create($name, $email, $zipCode, $position["city"], $password, $position["position"]);
    }

}
