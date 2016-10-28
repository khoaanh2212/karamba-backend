<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\DTO\DealerApplicationDTO;


/**
 * Class PendingDealerApplication
 * @package AppBundle\Entity
 */
interface PendingDealerApplication extends ISerializableDTO
{
    public function accept() : AcceptedDealerApplication;
    public function reject() : RejectedDealerApplication;
}