<?php

namespace AppBundle\DomainServices;

use AppBundle\Entity\StockCar;
use AppBundle\Registry\StockCarsRegistry;
use Doctrine\ORM\EntityNotFoundException;

class StockCarsDomainService
{

    /**
     * @var StockCarsRegistry
     */
    private $registry;

    public function __construct(StockCarsRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function addStockCar(StockCar $stockCar)
    {
        return $this->registry->saveOrUpdate($stockCar);
    }
    /**
     * @throws EntityNotFoundException
     */
    public function delete(string $id){
        $entity = $this->registry->findOneById($id);
        if(!$entity) {
            throw new EntityNotFoundException("stockCar with id [".$id."] not found");
        }
        $this->registry->delete($entity);
    }
    
    public function retrieveStockCarsByDealer($dealerId): array
    {
        return $this->registry->findByDealerId($dealerId);
    }

}
