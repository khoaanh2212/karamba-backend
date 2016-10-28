<?php

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\CarService;
use AppBundle\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class CarController extends Controller
{

    /**
     * @Route("/api/car/brands")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getBrands(Request $request) : JsonResponse
    {
        try{
            $brands = $this->getApplicationService()->getBrands();
            return new JsonResponse(new ApiResponse(0, $brands));
        }catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/car/brands/{brand}/models")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getModels($brand) : JsonResponse
    {
        try{
            $models = $this->getApplicationService()->getBrandModels($brand);
            return new JsonResponse(new ApiResponse(0, $models));
        }catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/car/brands/{brand}/models/{model}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getVehicle($brand, $model) : JsonResponse
    {
        try{
            $response = $this->getApplicationService()->getVehicle($brand, $model);
            return new JsonResponse(new ApiResponse(0, $response));
        }catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/car/vehicle/{vehicleId}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getVehicleExtras($vehicleId) : JsonResponse
    {
        return $this->doGetVehicleExtras($vehicleId);
    }

    /**
     * @Route("/api/car/vehicle/{vehicleId}/{packageId}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getVehicleExtrasWithPacks($vehicleId, $packageId) : JsonResponse
    {
        return $this->doGetVehicleExtras($vehicleId, $packageId);
    }

    private function doGetVehicleExtras(string $vehicleId, string $packageId = null) : JsonResponse
    {
        try{
            $response = $this->getApplicationService()->getVehicleExtrasAndPicture($vehicleId, $packageId);
            return new JsonResponse(new ApiResponse(0, $response));
        }catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    private function getApplicationService() : CarService
    {
        return $this->get("CarService");
    }

}
