<?php

namespace AppBundle\DomainServices;

use AppBundle\Registry\JatoRegistry;


class CarDomainService
{
    /**
     * @var JatoRegistry
     */
    private $registry;


    public function __construct(JatoRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getBrands()
    {
        return $this->registry->getBrands();
    }

    public function getBrandsModels(array $brands)
    {
        return $this->registry->getBrandsModels($brands);
    }
}
