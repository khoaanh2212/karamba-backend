<?php
namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\AcceptedDealerApplication;
use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\PendingDealerApplication;

class DealerApplicationRepository extends EntityRepository
{
    /**
     * @return PendingDealerApplication[]
     */
    public function findAllPending(): array
    {
        $query = $this->getEntityManager()
                    ->createQuery('SELECT application FROM AppBundle\Entity\DealerApplication application WHERE application.discr=\''.DealerApplication::PENDING.'\'');
        return $query->getResult();
    }

    /**
     * @return AcceptedDealerApplication[]
     */
    public function findAllAccepted(): array
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT application FROM AppBundle\Entity\DealerApplication application WHERE application.discr=\''.DealerApplication::ACCEPTED.'\'');
        return $query->getResult();
    }
}