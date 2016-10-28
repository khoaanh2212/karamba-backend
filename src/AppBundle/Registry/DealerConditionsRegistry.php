<?php

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class DealerConditionsRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:DealerCondition";
    }

    protected function tableName() : string
    {
        return "dealerconditions";
    }
}