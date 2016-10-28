<?php

namespace AppBundle\DomainServices;


use AppBundle\Entity\DealerCondition;
use AppBundle\Registry\DealerConditionsRegistry;

class DealerConditionDomainService
{
    /**
     * @var DealerConditionsRegistry
     */
    private $registry;

    public function __construct(DealerConditionsRegistry $registry)
    {
        $this->registry = $registry;
    }


    /**
     * @return DealerCondition[]
     */
    public function getAllConditions() : array
    {
        return $this->registry->findAll();
    }
}