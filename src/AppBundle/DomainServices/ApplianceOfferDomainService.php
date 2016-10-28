<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/08/16
 * Time: 12:27
 */

namespace AppBundle\DomainServices;


use AppBundle\Entity\ApplianceOffer;
use AppBundle\Registry\ApplianceOfferRegistry;
use AppBundle\Registry\CarApplianceRegistry;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityNotFoundException;

class ApplianceOfferDomainService
{
    /**
     * @var ApplianceOfferRegistry
     */
    private $registry;

    /**
     * @var CarApplianceRegistry
     */
    private $carApplianceRegistry;

    public function __construct(ApplianceOfferRegistry $registry, CarApplianceRegistry $carApplianceRegistry)
    {
        $this->registry = $registry;
        $this->carApplianceRegistry = $carApplianceRegistry;
    }

    public function createOffersForCarAppliance(array $dealersIds, string $applianceId)
    {
        $result = array();
        foreach ($dealersIds as $dealerId) {
            array_push($result, $this->createOfferForCarAppliance($dealerId, $applianceId));
        }
        return $result;
    }

    public function markApplianceOfferAsNewMessage(string $applianceId)
    {
        $this->updateAppliance($applianceId, "markAsNewMessage");
    }

    public function markApplianceOfferAsReplied(string $applianceId)
    {
        $this->updateAppliance($applianceId, "markAsReplied");
    }

    private function updateAppliance(string $applianceId, string $method)
    {
        $applianceOffer = $this->registry->findOneById($applianceId);
        if (!$applianceOffer) {
            throw new EntityNotFoundException();
        }
        $applianceOffer->$method();
        $this->registry->saveOrUpdate($applianceOffer);
    }

    public function createOfferForCarAppliance(string $dealerId, string $applianceId)
    {
        $offer = new ApplianceOffer($dealerId, $applianceId);
        return $this->registry->saveOrUpdate($offer);
    }

    public function makeAnOffer(string $dealerId, string $offerId, float $cashPrice, $foundedPrice, bool $inStock)
    {
        $applianceOffer = $this->registry->findOneById($offerId);
        $applianceId = $applianceOffer->getApplianceId();
        $appliance = $this->carApplianceRegistry->findOneById($applianceId);

        if (!$appliance->isAvailableForOffers()) {
            throw new InvalidArgumentException();
        }
        $applianceOffer->makeAnOffer($dealerId, $cashPrice, $foundedPrice, $inStock);
        $this->registry->saveOrUpdate($applianceOffer);
        $appliance->addOffer();
        $this->carApplianceRegistry->saveOrUpdate($appliance);

        if (!$appliance->isAvailableForOffers()) {
            $this->registry->expireOffers($applianceId);
        }
        return $this->carApplianceRegistry->findOneApplianceOffer($offerId);
    }

    public function markAsRead(string $offerId)
    {
        $applianceOffer = $this->registry->findOneById($offerId);
        if (!$applianceOffer) {
            throw new EntityNotFoundException();
        }
        $applianceOffer->markAsRead();
        $this->registry->saveOrUpdate($applianceOffer);
    }

    public function findApplianceOfferById(string $id) : ApplianceOffer
    {
        return $this->registry->findOneById($id);
    }

    public function findAllOffersForAppliance(string $applianceId)
    {
        return $this->registry->findAllOffersForAppliance($applianceId);
    }
}