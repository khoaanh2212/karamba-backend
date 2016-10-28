<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 15:21
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class CarApplianceRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:CarAppliance";
    }

    protected function tableName() : string
    {
        return "carappliances";
    }
}