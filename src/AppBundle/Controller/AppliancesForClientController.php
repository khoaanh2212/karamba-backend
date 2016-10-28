<?php
/**
 * Created by PhpStorm.
 * User: ka
 * Date: 14/10/2016
 * Time: 09:30
 */

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\AppliancesForClientService;
use AppBundle\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;


class AppliancesForClientController extends Controller
{
    /**
     * @Route("/api/client/conversation-list")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getListOfferMessage()
    {
        try {
            $clientId = $this->getUser()->getId();
            $conversationList = $this->getAppliancesForClientService()->findListOfferHaveAtLeastOneMessageFromClientByClientId($clientId);
            return new JsonResponse(new ApiResponse(0, $conversationList));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }

    }

    /**
     * @Route("/api/client/review/dealers")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getListOfferDealersName()
    {
        try {
            $clientId = $this->getUser()->getId();
            $result = $this->getAppliancesForClientService()->findOffersDealersNameFromClientByClientId($clientId);
            return new JsonResponse(new ApiResponse(0, $result));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), $e->getMessage()), 500);
        }

    }

    private function getAppliancesForClientService(): AppliancesForClientService
    {
        return $this->get("AppliancesForClientService");
    }
}