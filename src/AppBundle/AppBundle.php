<?php

namespace AppBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    private static $booted = false;
    public function boot()
    {
        if(!self::$booted) {
            Type::addType('point', 'AppBundle\Utils\PointType');
        }
        self::$booted = true;
    }
}
