<?php

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;


class DealerApplicationRegistry extends RegistryBase
{
    /**
     * @return string
     */
    protected function entityQualifiedName(): string
    {
        return "AppBundle:DealerApplication";
    }

    protected function tableName() : string
    {
        return "dealerapplications";
    }
}