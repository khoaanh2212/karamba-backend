<?php

namespace AppBundle\ApplicationServices;


use AppBundle\Utils\SessionStorageFactory;

class LogoutService
{
    public function logout() {
        SessionStorageFactory::getInstance()->removeValue("TOKEN_ID");
    }
}