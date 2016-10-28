<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 19/07/16
 * Time: 15:52
 */

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\DealerService;
use AppBundle\DTO\DealerDTO;
use AppBundle\DTO\DealerWithConditionsDTO;
use AppBundle\Entity\Dealer;
use AppBundle\Utils\SessionStorageFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DealerController extends Controller
{
    /**
     * @Route("/api/dealer/login")
     * @Method({"POST"})
     * @return Response
     */
    public function login()
    {
        return new JsonResponse(
            array(
                "token" => SessionStorageFactory::getInstance()->getValue("TOKEN_ID"),
                "first_use" => $this->getCurrentUser()->profile->firstUse
            )
        );
    }

    /**
     * @Route("/api/dealer/current")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentDealer(Request $request)
    {
        try {
            return new JsonResponse(new ApiResponse(0, $this->getCurrentUser()));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }


    /**
     * @Route("/api/dealer/current")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDealer(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $jsonRequest = json_decode($request->request->get("updateData"));
            $name = isset($jsonRequest->name) ? $jsonRequest->name : null;
            $description = isset($jsonRequest->description) ? $jsonRequest->description : null;
            $address = isset($jsonRequest->address) ? $jsonRequest->address : null;
            $phone = isset($jsonRequest->phone) ? $jsonRequest->phone : null;
            $zipCode = isset($jsonRequest->zipCode) ? $jsonRequest->zipCode : null;
            $vendorName = isset($jsonRequest->vendorName) ? $jsonRequest->vendorName : null;
            $vendorRole = isset($jsonRequest->vendorRole) ? $jsonRequest->vendorRole : null;
            $password = isset($jsonRequest->password) ? $jsonRequest->password : null;
            $scheduling = isset($jsonRequest->schedule) ? $jsonRequest->schedule : null;
            $deliveryConditions = isset($jsonRequest->deliveryConditions) ? $jsonRequest->deliveryConditions : null;
            $specialConditions = isset($jsonRequest->specialConditions) ? $jsonRequest->specialConditions : null;
            $generalConditionsIds = isset($jsonRequest->generalConditions) ? $jsonRequest->generalConditions : null;
            $avatarFile = $request->files->get("avatar");
            $backgroundImage = $request->files->get("background");
            $this->getDealerService()->updateDealer($id, $name, $description, $phone, $vendorName, $vendorRole, $password, $address, $scheduling, $deliveryConditions, $specialConditions, $generalConditionsIds, $avatarFile, $backgroundImage, $zipCode);
            return new JsonResponse(new ApiResponse(0, $this->getCurrentUser()));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/public/dealer")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function createDealer(Request $request)
    {
        try {
            $jsonRequest = json_decode($request->getContent());
            $token = $jsonRequest->token;
            $password = $jsonRequest->password;
            $this->getDealerService()->createDealer($token, $password);
            return new JsonResponse(new ApiResponse(0, "OK"));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    private function getDealerService() : DealerService
    {
        return $this->get("DealerService");
    }

    private function getCurrentUser() : DealerWithConditionsDTO
    {
        return $this->getDealerService()->getDealerById($this->getUser()->getId());
    }

    /**
     * @Route("/api/dealer/cars")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAvailableCars(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $content = $this->validateRequest($request);

            $carsByBrand = json_decode($content);

            $this->getDealerService()->updateAvailableCars($id, $carsByBrand);
            return new JsonResponse(new ApiResponse(0, "OK"));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/cars")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAvailableCars(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $cars = $this->getDealerService()->getCarsWithAvailability($id);
            return new JsonResponse(new ApiResponse(0, $cars));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/stock")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addStockCar(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $content = $this->validateRequest($request);
            $car = json_decode($content);
            return new JsonResponse(new ApiResponse(0, $this->getDealerService()->addStockCar($id, $car)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/stock")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStockCar(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $content = $this->validateRequest($request);
            $car = json_decode($content);
            return new JsonResponse(new ApiResponse(0, $this->getDealerService()->updateStockCar($id, $car)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/stock/{id}")
     * @Method({"DELETE"})
     * @return JsonResponse
     */
    public function deleteStockCar($id)
    {
        try {
            $this->getDealerService()->deleteStockCar($id);
            return new JsonResponse(new ApiResponse(0, "OK"));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/stock")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getStockCars(Request $request)
    {
        try {
            $dealerId = $this->getUser()->getId();
            return new JsonResponse(new ApiResponse(0, $this->getDealerService()->getStockCars($dealerId)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealer/rating")
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function getRating(Request $request)
    {
        try {
            $dealerId = $this->getUser()->getId();
            return new JsonResponse(new ApiResponse(0, $this->getDealerService()->getRating($dealerId)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    private function validateRequest(Request $request)
    {
        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException("Content is empty");
        }
        return $content;
    }

}
