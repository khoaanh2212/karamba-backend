<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 11/10/16
 * Time: 16:35
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class OfferMessageFileRegistry extends RegistryBase
{
    protected function entityQualifiedName(): string
    {
        return "AppBundle:OfferMessageFile";
    }

    protected function tableName() : string
    {
        return "offermessagefiles";
    }
}