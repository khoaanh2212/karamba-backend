<?php

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\PacksService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;

class PacksController extends Controller
{
    /**
     * @Route("/api/car/packs/{vehicleId}")
     * @Method({"GET"})
     * @param $vehicleId
     * @return JsonResponse
     * @internal param Request $request
     */
    public function getPacks($vehicleId): JsonResponse
    {
        try {
            $response = $this->getPackApplicationService()->getPacksForVehicleId($vehicleId);
            return new JsonResponse(new ApiResponse(0, $response));
        } catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    private function getPackApplicationService(): PacksService
    {
        return $this->get("PacksService");
    }
}