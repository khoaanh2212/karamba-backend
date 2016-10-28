<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 20/07/16
 * Time: 11:12
 */

namespace AppBundle\Controller;


use AppBundle\Utils\SessionStorageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;


class AdminLoginController extends Controller
{
    /**
     * @Route("/api/admin/login")
     */
    public function login()
    {
        return new JsonResponse(
            array(
                "username" => $this->getUser()->getUsername(),
                "token" => SessionStorageFactory::getInstance()->getValue("TOKEN_ID")
            )
        );
    }
}