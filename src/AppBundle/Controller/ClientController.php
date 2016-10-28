<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 17:30
 */

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\ClientService;
use AppBundle\Utils\ApiResponse;
use AppBundle\Utils\SessionStorageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{

    /**
     * @Route("/api/client/login")
     * @Method({"POST"})
     * @return Response
     */
    public function login()
    {
        return new JsonResponse(
            array(
                "token" => SessionStorageFactory::getInstance()->getValue("TOKEN_ID")
            )
        );
    }


    /**
     * @Route("/api/public/client")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function createClient(Request $request)
    {
        try {
            $jsonRequest = json_decode($request->getContent());
            $name = $jsonRequest->name;
            $email = $jsonRequest->email;
            $zipCode = $jsonRequest->zipCode;
            $password = $jsonRequest->password;
            $appliance = $jsonRequest->carAppliance;
            $vehicleId = $appliance->vehicleId;
            $brand = $appliance->brand;
            $model = $appliance->model;
            $extras = $appliance->selectedExtra->extras;
            $color = null;
            if(property_exists($appliance->selectedExtra, "color")) {
                $color = $appliance->selectedExtra->color;
            }
            $package = null;
            if(property_exists($appliance, "selectedPackage")) {
                $package = $appliance->selectedPackage;
            }
            $this->getClientService()->createClientAndAppliance($name, $email, $zipCode, $password, $vehicleId, $brand, $model, $extras, $package, $color);
            return new JsonResponse(new ApiResponse(0, "OK"));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/client/current")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentClient(Request $request)
    {
        try {
            return new JsonResponse(new ApiResponse(0, $this->getCurrentUser()));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/client/current")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateClient(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $jsonRequest = json_decode($request->request->get("updateData"));
            $name = isset($jsonRequest->name)?$jsonRequest->name:null;
            $zipCode = isset($jsonRequest->zipCode)?$jsonRequest->zipCode:null;
            $password = isset($jsonRequest->password)?$jsonRequest->password:null;
            $this->getClientService()->updateClient($id, $name, $zipCode, $password);
            return new JsonResponse(new ApiResponse(0, $this->getCurrentUser()));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    private function getCurrentUser()
    {
        return $this->getClientService()->getClientById($this->getUser()->getId());
    }


    /**
     * @Route("/api/client/gifts")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getGifts() : JsonResponse
    {
        try{
            $gifts = $this->getClientService()->getClientGifts();
            return new JsonResponse(new ApiResponse(0, $gifts));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/client/rating/{dealerId}")
     * @Method("GET")
     * @return JsonResponse
     */
    public function getRatingByDealerId($dealerId)
    {
        try {
            return new JsonResponse(new ApiResponse(0, $this->getClientService()->getRatingOfDealer($dealerId)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    private function getClientService() : ClientService
    {
        return $this->get("ClientService");
    }
}
