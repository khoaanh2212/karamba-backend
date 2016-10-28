<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\DealerCondition;

class DealerConditionRepository extends EntityRepository
{
    /**
     * @param int[] $ids
     * @return DealerCondition[]
     */
    public function findAllByIds(array $ids): array
    {
        return $this->createQueryBuilder("cond")
                    ->where('cond.id IN (:ids)')
                    ->setParameter('ids', $ids)
                    ->getQuery()->getResult();
    }
}