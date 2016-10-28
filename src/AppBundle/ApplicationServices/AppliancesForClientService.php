<?php
namespace AppBundle\ApplicationServices;

use AppBundle\DomainServices\AppliancesForClientDomainService;
use phpDocumentor\Reflection\Types\String_;

class AppliancesForClientService
{
    /**
     * @var AppliancesForClientDomainService
     */
    private $appliancesForClientDomainService;

    public function __construct(AppliancesForClientDomainService $appliancesForClientDomainService)
    {
        $this->appliancesForClientDomainService = $appliancesForClientDomainService;
    }

    public function findListOfferHaveAtLeastOneMessageFromClientByClientId(String $clientId)
    {
        $conversationList = $this->appliancesForClientDomainService->findListOfferHaveAtLeastOneMessageFromClientByClientId($clientId);
        return array(
            'conversationList' => $conversationList
        );
    }

    public function findOffersDealersNameFromClientByClientId(String $clientId)
    {
        $result = $this->appliancesForClientDomainService->findOffersDealersNameFromClientByClientId($clientId);
        return array(
            'dealers' => $result
        );
    }
}