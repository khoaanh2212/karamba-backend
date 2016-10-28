<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 30/08/16
 * Time: 11:27
 */

namespace AppBundle\DomainServices;


use AppBundle\Registry\CarApplianceRegistry;

class AppliancesForDealerDomainService
{
    /**
     * @var CarApplianceRegistry
     */
    private $registry;

    public function __construct(CarApplianceRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function findApplianceOffersForDealer(string $dealerId)
    {
        return $this->registry->findApplianceOffersForDealer($dealerId);
    }

    public function getAppliance(string $applianceId)
    {
        return $this->registry->findOneApplianceOffer($applianceId);
    }

    public function findApplianceOffersArchivedForDealer(string $dealerId)
    {
        return $this->registry->findApplianceOffersArchivedForDealer($dealerId);
    }

    public function findOffersHasAtLeastOneMessageForDealer(string $dealerId)
    {
        return $this->registry->findOffersHasAtLeastOneMessageForDealer($dealerId);
    }
}