<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 15/09/16
 * Time: 10:39
 */

namespace AppBundle\Controller;


use AppBundle\ApplicationServices\AppliancesForDealerService;
use AppBundle\ApplicationServices\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;

class AppliancesForDealerController extends Controller
{
    /**
     * @Route("/api/dealers/offers")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function findApplianceOffersForDealer(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $offers = $this->getAppliancesForDealerService()->findApplianceOffersForDealer($id);
            return new JsonResponse(new ApiResponse(0, $offers));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/dealers/offers/archived")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function findOffersArchivedForDealer(Request $request)
    {
        try {
            $id = $this->getUser()->getId();
            $offers = $this->getAppliancesForDealerService()->getApplianceOffersArchivedForDealer($id);
            return new JsonResponse(new ApiResponse(0, $offers));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getMessage(), "ERROR"), 500);
        }
    }


    /**
     * @Route("/api/dealers/conversations")
     * @Method("GET")
     * @return JsonResponse
     */
    public function getConversations()
    {
        try {
            $id = $this->getUser()->getId();
            $offers = $this->getAppliancesForDealerService()->getOffersHasConversationsForDealer($id);
            return new JsonResponse(new ApiResponse(0, $offers));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }
    }

    /**
     * @Route("/api/dealers/offer/{offerId}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getApplianceOffer($offerId)
    {
        try {
            $id = $this->getUser()->getId();
            $offer = $this->getAppliancesForDealerService()->getApplianceDetail($id, $offerId);
            return new JsonResponse(new ApiResponse(0, $offer));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("/api/dealers/offer/{offerId}")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function makeAnOffer($offerId)
    {
        try {
            $id = $this->getUser()->getId();
            $request = Request::createFromGlobals()->getContent();
            $data = json_decode($request);
            $cashPrize = floatval($data->cashPrize);
            $foundedPrize = null;
            if (property_exists($data, "foundedPrize")) {
                $foundedPrize = floatval($data->foundedPrize);
            }
            $inStock = $data->inStock;
            $message = $data->message;
            $this->getOfferService()->makeAnOffer($id, $offerId, $cashPrize, $foundedPrize, $inStock, $message);
            return new JsonResponse(new ApiResponse(0, "OK"));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    /**
     * @Route("api/dealer/statistic")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getStatistics()
    {
        try {
            return new JsonResponse(new ApiResponse(0, array(
                "statistic" => array(
                    array(
                        "status" => "received",
                        "quantity" => 180
                    ),
                    array(
                        "status" => "expired",
                        "quantity" => 72
                    ),
                    array(
                        "status" => "requested",
                        "quantity" => 43
                    ),
                    array(
                        "status" => "performed",
                        "quantity" => 92
                    ),
                    array(
                        "status" => "won",
                        "quantity" => 19
                    ),
                    array(
                        "status" => "lost",
                        "quantity" => 25
                    ),
                )
            )));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"), 500);
        }
    }

    private function getAppliancesForDealerService(): AppliancesForDealerService
    {
        return $this->get("AppliancesForDealerService");
    }

    private function getOfferService(): OfferService
    {
        return $this->get("OfferService");
    }
}