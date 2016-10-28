<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class AvatarRepository extends EntityRepository
{
    public function findByDealerIds(array $dealerIds)
    {
        return $this->createQueryBuilder("avatar")
            ->where("avatar.dealerId IN (:dealerIds)")
            ->setParameter("dealerIds", $dealerIds)
            ->getQuery()->execute();
    }
}