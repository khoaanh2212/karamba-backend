<?php

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class ReviewRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:Review";
    }

    protected function tableName() : string
    {
        return "reviews";
    }
}