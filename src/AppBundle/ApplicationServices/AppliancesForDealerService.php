<?php

namespace AppBundle\ApplicationServices;


use AppBundle\DomainServices\AppliancesForDealerDomainService;
use AppBundle\DomainServices\StockCarsDomainService;
use AppBundle\DomainServices\ApplianceOfferDomainService;

class AppliancesForDealerService
{
    /**
     * @var AppliancesForDealerDomainService
     */
    private $applianceForDealerDomainService;

    /**
     * @var StockCarsDomainService
     */
    private $stockCarsDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;

    public function __construct(AppliancesForDealerDomainService $applianceForDealerDomainService, StockCarsDomainService $stockCarsDomainService, ApplianceOfferDomainService $applianceOfferDomainService)
    {
        $this->applianceForDealerDomainService = $applianceForDealerDomainService;
        $this->stockCarsDomainService = $stockCarsDomainService;
        $this->applianceOfferDomainService = $applianceOfferDomainService;
    }

    public function findApplianceOffersForDealer(string $dealerId)
    {
        $appliances = $this->applianceForDealerDomainService->findApplianceOffersForDealer($dealerId);
        return array(
            "opportunities" => $appliances
        );
    }

    public function getApplianceDetail(string $dealerId, string $applianceOfferId)
    {
        $cars = $this->stockCarsDomainService->retrieveStockCarsByDealer($dealerId);
        $appliance = $this->applianceForDealerDomainService->getAppliance($applianceOfferId);
        $offer = $this->applianceOfferDomainService->findApplianceOfferById($applianceOfferId);

        $similar = false;
        foreach ($cars as $car) {
            if ($car->getBrand() == $appliance->brand && $car->getModel() == $appliance->model) {
                $similar = true;
            }
        }

        if ($similar) {
            $appliance->markInStock();
        }

        $this->applianceOfferDomainService->markAsRead($appliance->offerId);

        $appliance->offer = $offer->toDTO();
        return $appliance;
    }

    public function getApplianceOffersArchivedForDealer(string $dealerId)
    {
        $offers = $this->applianceForDealerDomainService->findApplianceOffersArchivedForDealer($dealerId);
        return array(
            "archived" => $offers
        );
    }

    public function getOffersHasConversationsForDealer(string $dealerId)
    {
        $offers = $this->applianceForDealerDomainService->findApplianceOffersForDealer($dealerId);
        $offersHasMessage = $this->applianceForDealerDomainService->findOffersHasAtLeastOneMessageForDealer($dealerId);

        if (count($offersHasMessage) == 0)
            return array();

        $results = array();
        foreach ($offers as $offer) {
            foreach ($offersHasMessage as $offerHasMessage){
                if($offer->id == $offerHasMessage["offer_id"]){
                    array_push($results,$offer);
                    break 1;
                }
            }
        }
        return $results;
    }
}