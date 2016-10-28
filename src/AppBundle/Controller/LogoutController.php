<?php

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\LogoutService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LogoutController extends Controller
{
    /**
     * @Route("/api/logout")
     * @Method({"POST"})
     * @return Response
     */
    public function logout() {
        try {
            $this->getService()->logout();
            $jsonResponse = new JsonResponse(new ApiResponse(0, null));
            $jsonResponse->headers->add(array("X-ACCEL-REDIRECT" => "/logout"));
            return $jsonResponse;
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    private function getService(): LogoutService
    {
        return $this->get("LogoutService");
    }
}