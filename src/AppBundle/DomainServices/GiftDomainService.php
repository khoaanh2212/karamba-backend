<?php

namespace AppBundle\DomainServices;

use AppBundle\Entity\Gift;
use AppBundle\Registry\GiftRegistry;
use Doctrine\ORM\EntityNotFoundException;

class GiftDomainService
{
    /**
     * @var GiftRegistry
     */
    private $registry;

    public function __construct(GiftRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create(string $value, string $name): Gift
    {
        $gift = new Gift($value, $name);
        return $this->registry->saveOrUpdate($gift);
    }

    public function findGiftById(string $id)
    {
        return $this->registry->findOneById($id);
    }


    /**
     * @throws EntityNotFoundException
     * @return Gift
     */
    public function findGifts()
    {
        return $this->registry->findAll();
    }
}