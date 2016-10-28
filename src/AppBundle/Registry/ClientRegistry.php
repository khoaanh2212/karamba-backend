<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 16:15
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class ClientRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:Client";
    }

    protected function tableName() : string
    {
        return "clients";
    }
}