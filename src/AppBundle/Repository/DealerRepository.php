<?php

namespace AppBundle\Repository;


use AppBundle\Utils\Point;
use Doctrine\ORM\EntityRepository;

class DealerRepository extends EntityRepository
{
    public function findDealerIdsByModelInPosition(string $brand, string $model, Point $topWestSquareEnvelope, Point $lowEastSquareEnvelope)
    {
        $rlon1 = $topWestSquareEnvelope->getLongitude();
        $rlon2 = $lowEastSquareEnvelope->getLongitude();
        $rlat1 = $topWestSquareEnvelope->getLatitude();
        $rlat2 = $lowEastSquareEnvelope->getLatitude();
        $sql = "select
                DISTINCT (id)
                from dealerscarsandpositions
                where within(position, envelope(linestring(point($rlon1, $rlat1), point($rlon2, $rlat2))))
                AND ((stockBrands='$brand' AND stockModel='$model')
                OR (availableBrands='$brand' AND availableModels like '%$model%'));";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        return $sqlStatement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function findByIds(array $ids)
    {
        return $this->createQueryBuilder("dealers")
                ->where("dealers.id in (:ids)")
                ->setParameter("ids", $ids)
                ->getQuery()->execute();
    }

    public function findDealerIdsByModel(string $brand, string $model)
    {
        $sql = "select
                DISTINCT (id)
                from dealerscarsandpositions
                WHERE ((stockBrands='$brand' AND stockModel='$model')
                OR (availableBrands='$brand' AND availableModels like '%$model%'));";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        return $sqlStatement->fetchAll(\PDO::FETCH_COLUMN);
    }
}