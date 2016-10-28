<?php

namespace AppBundle\SecurityProviders;

use AppBundle\Entity\Administrator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class AdminProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function loadUserByUsername($username)
    {
        return new Administrator($this->username, $this->password);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class == "AppBundle\\Entity\\Administrator";
    }
}