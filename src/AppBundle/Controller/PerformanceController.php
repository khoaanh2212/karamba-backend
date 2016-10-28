<?php

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;

class PerformanceController extends Controller
{
    /**
     * @Route("/api/car/performances")
     * @Method({"POST"})
     * @return JsonResponse
     * @internal param Request $request
     */
    public function getPerformances(Request $request): JsonResponse
    {
        try {
            $vehicles = json_decode($request->getContent());
            $response = $this->getApplicationService()->getPerformances($vehicles);
            return new JsonResponse(new ApiResponse(0, $response));
        } catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    private function getApplicationService(): CarService
    {
        return $this->get("CarService");
    }
}