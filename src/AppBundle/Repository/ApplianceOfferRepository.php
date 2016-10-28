<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 16:25
 */

namespace AppBundle\Repository;


use AppBundle\Utils\ApplianceOfferState;
use Doctrine\ORM\EntityRepository;

class ApplianceOfferRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder("offer")
            ->where('offer.state <> (:state)')
            ->setParameter('state', ApplianceOfferState::EXPIRED)
            ->getQuery()->getResult();
    }

    public function expireOffers(string $applianceId)
    {
        $this->createQueryBuilder("offer")
            ->update()
            ->set("offer.state", '\''.ApplianceOfferState::EXPIRED.'\'')
            ->where("offer.applianceId = (:applianceId)")
            ->andWhere("offer.state = (:state)")
            ->setParameter('applianceId', $applianceId)
            ->setParameter('state', ApplianceOfferState::NEW_OPPORTUNITY)
            ->getQuery()->execute();
    }

    public function findAllOffersForAppliance(string $applianceId)
    {
        return $this->createQueryBuilder("offer")
            ->where("offer.applianceId = (:applianceId)")
            ->andWhere('offer.state <> (:state)')
            ->setParameter('applianceId', $applianceId)
            ->setParameter('state', ApplianceOfferState::NEW_OPPORTUNITY)
            ->orderBy('offer.cashPrice', 'ASC')
            ->getQuery()->getResult();
    }
}