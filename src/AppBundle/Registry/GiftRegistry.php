<?php
/**
 * Created by IntelliJ IDEA.
 * User: apium
 * Date: 10/18/16
 * Time: 5:52 PM
 */


namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class GiftRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:Gift";
    }

    protected function tableName() : string
    {
        return "gifts";
    }
}