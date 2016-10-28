<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 16:26
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;


class ApplianceOfferRegistry extends RegistryBase
{

    protected function entityQualifiedName(): string
    {
        return "AppBundle:ApplianceOffer";
    }

    protected function tableName() : string
    {
        return "applianceOffers";
    }
}