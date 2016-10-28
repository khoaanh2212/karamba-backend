<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/08/16
 * Time: 18:48
 */

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\CarApplianceService;
use AppBundle\ApplicationServices\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;

class CarApplianceController extends Controller
{

    /**
     * @Route("/api/client/appliances")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppliances(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $service = $this->getCarApplianceService();
            $appliances = $service->getAppliancesForClient($id);
            return new JsonResponse(new ApiResponse(0, $appliances));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/client/appliances")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAppliance(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $appliance = json_decode($request->getContent());
            $vehicleId = $appliance->vehicleId;
            $brand = $appliance->brand;
            $model = $appliance->model;
            $extras = $appliance->selectedExtra->extras;
            $color = null;
            if (property_exists($appliance->selectedExtra, "color")) {
                $color = $appliance->selectedExtra->color;
            }
            $package = null;
            if (property_exists($appliance, "selectedPackage")) {
                $package = $appliance->selectedPackage;
            }
            $service = $this->getCarApplianceService();
            $service->createAppliance($id, $vehicleId, $brand, $model, $extras, $package, $color);
            return new JsonResponse(new ApiResponse(0, null));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/client/appliances/{id}")
     * @Method({"DELETE"})
     * @return JsonResponse
     */
    public function deleteAppliance($id)
    {
        try {
            $this->getCarApplianceService()->deleteAppliance($id);
            return new JsonResponse(new ApiResponse(0, "OK"));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @param $id
     * @Route("/api/client/appliances/{id}/offers")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getOffersForAppliance($id)
    {
        try {
            $client = $this->getUser();
            return new JsonResponse(new ApiResponse(0, $this->getOffersService()->getOffersForAppliance($id, $client)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/client/messages/thread/{offerId}")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addMessageAsClient(Request $request, string $offerId)
    {
        return $this->_addMessage($request, $offerId, true);
    }

    /**
     * @Route("/api/dealer/messages/thread/{offerId}")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addMessageAsDealer(Request $request, string $offerId)
    {
        return $this->_addMessage($request, $offerId, false);
    }

    /**
     * @Route("/api/client/offer/{offerId}/dealer")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getDealerAndOfferDetail(string $offerId)
    {
        try {
            $service = $this->getOffersService();
            return new JsonResponse(new ApiResponse(0, $service->getOfferDetailForOffer($offerId)));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/client/messages/thread/{offerId}")
     * @Method({"GET"})
     * @param $offerId
     * @return JsonResponse
     */
    public function getThread($offerId)
    {
        return $this->getMessageThread($offerId, "client");
    }

    private function getMessageThread($offerId, $viewAs)
    {
        try {
            $service = $this->getCarApplianceService();
            $thread = $service->getThread($offerId, $viewAs);
            return new JsonResponse(new ApiResponse(0, $thread));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/dealer/messages/thread/{offerId}")
     * @Method({"GET"})
     * @param $offerId
     * @return JsonResponse
     */
    public function getThreadDealer($offerId)
    {
        return $this->getMessageThread($offerId, "dealer");
    }

    private function getCarApplianceService() : CarApplianceService
    {
        return $this->get("CarApplianceService");
    }

    private function getOffersService() : OfferService
    {
        return $this->get("OfferService");
    }

    private function _addMessage(Request $request, string $offerId, $isClient)
    {
        try {
            $attachment = $request->files->get("attachment");
            $service = $this->getCarApplianceService();
            $body = json_decode($request->request->get("data"));
            $id = $this->getUser()->getId();
            if ($isClient) {
                $service->addMessageAsClient($id, $offerId, $body->message, $attachment);
            } else {
                $service->addMessageAsDealer($id, $offerId, $body->message, $attachment);
            }
            return new JsonResponse(new ApiResponse(0, $offerId));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }
}