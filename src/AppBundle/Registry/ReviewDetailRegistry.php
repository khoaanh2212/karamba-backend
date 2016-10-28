<?php

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class ReviewDetailRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:ReviewDetail";
    }

    protected function tableName() : string
    {
        return "reviewdetails";
    }
}