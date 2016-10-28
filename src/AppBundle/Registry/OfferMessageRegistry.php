<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 29/08/16
 * Time: 14:43
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class OfferMessageRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:OfferMessage";
    }

    protected function tableName() : string
    {
        return "offermessages";
    }
}