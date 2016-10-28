<?php
/**
 * Created by PhpStorm.
 * User: ka
 * Date: 13/10/2016
 * Time: 18:26
 */

namespace AppBundle\DomainServices;


use AppBundle\Registry\CarApplianceRegistry;

class AppliancesForClientDomainService
{
    /**
     * @var CarApplianceRegistry
     */
    private $registry;

    public function __construct(CarApplianceRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function findListOfferHaveAtLeastOneMessageFromClientByClientId(string $clientId)
    {
        return $this->registry->findListOfferHaveAtLeastOneMessageFromClientByClientId($clientId);
    }

    public function findOffersDealersNameFromClientByClientId(string $clientId)
    {
        return $this->registry->findOffersDealersNameFromClientByClientId($clientId);
    }
}