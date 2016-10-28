<?php

namespace AppBundle\SecurityProviders;

use AppBundle\Entity\Administrator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use AppBundle\Utils\SessionStorageFactory;

class AdminLoginSecurityProvider extends UserNameAndPasswordSecurityProvider
{
    /**
     * @var string
     */
    private $adminUserName;

    /**
     * @var string
     */
    private $adminPassword;

    public function __construct(string $adminUserName, string $adminPassword)
    {
        $this->adminUserName = $adminUserName;
        $this->adminPassword = $adminPassword;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new Administrator($this->adminUserName, $this->adminPassword);
    }
}