<?php

namespace AppBundle\Registry;

use AppBundle\Utils\RegistryBase;

class StockCarsRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:StockCar";
    }

    protected function tableName() : string
    {
        return "stockcars";
    }
}
