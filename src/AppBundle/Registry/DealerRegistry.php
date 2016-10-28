<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 15/07/16
 * Time: 18:07
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class DealerRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:Dealer";
    }

    protected function tableName() : string
    {
        return "dealers";
    }
}