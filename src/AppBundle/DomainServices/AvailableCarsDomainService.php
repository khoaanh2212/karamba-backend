<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/07/16
 * Time: 18:18
 */

namespace AppBundle\DomainServices;

use AppBundle\Entity\AvailableCars;
use AppBundle\Registry\AvailableCarsRegistry;

class AvailableCarsDomainService
{

    /**
     * @var AvailableCarsRegistry
     */
    private $registry;

    public function __construct(AvailableCarsRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function updateAvailableCarsByDealer($availableCarsArray, $dealerId)
    {
        $existingBrands = $this->registry->findByDealerId($dealerId);
        foreach($existingBrands as $brand){
            $this->registry->delete($brand);
        }
        foreach($availableCarsArray as $newBrand){
            $this->registry->saveOrUpdate($newBrand);
        }
    }

    public function retrieveAvailableCarsByDealer($dealerId)
    {
        return $this->registry->findByDealerId($dealerId);
    }

}
