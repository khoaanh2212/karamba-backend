<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/07/16
 * Time: 17:06
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class AvailableCarsRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:AvailableCars";
    }

    protected function tableName() : string
    {
        return "availablecars";
    }
}