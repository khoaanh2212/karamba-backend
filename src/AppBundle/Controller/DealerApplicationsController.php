<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use AppBundle\ApplicationServices\DealerApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use AppBundle\Utils\ApiResponse;

class DealerApplicationsController extends Controller
{
    /**
     * @Route("/api/dealer/application")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function listAllPendingApplications()
    {
        try{
            return new JsonResponse(new ApiResponse(0, $this->getApplicationService()->listAllPendingApplications()));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/dealer/application/{id}")
     * @Method({"DELETE"})
     * @return JsonResponse
     */
    public function rejectApplication($id)
    {
        try{
            $this->getApplicationService()->rejectApplication($id);
            return new JsonResponse(new ApiResponse(0, "OK"));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/dealer/application/{id}")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function acceptApplication($id)
    {
        try{
            return new JsonResponse(new ApiResponse(0, $this->getApplicationService()->acceptApplication($id)));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/public/dealer/application/accepted/{token}")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAcceptedApplicationByToken($token)
    {
        try{
            return new JsonResponse(new ApiResponse(0, $this->getApplicationService()->retrieveApplicationAndValidate($token)));
        }
        catch(EntityNotFoundException $e) {
            $code = 404;
            $jsonResponse = new JsonResponse(new ApiResponse($code, "ERROR"));
            $jsonResponse->setStatusCode($code);
            return $jsonResponse;
        }
        catch(NonceExpiredException $e) {
            $code = 401;
            $jsonResponse = new JsonResponse(new ApiResponse($code, "ERROR"));
            $jsonResponse->setStatusCode($code);
            return $jsonResponse;
        }
        catch(\Exception $e) {
            $code = $e->getCode();
            $jsonResponse = new JsonResponse(new ApiResponse($code, "ERROR"));
            $jsonResponse->setStatusCode($code);
            return $jsonResponse;
        }
    }

    /**
     * @Route("/api/public/dealer/application/create")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function createApplication(Request $request)
    {
        $content = $request->getContent();

        if(empty($content)){
            throw new BadRequestHttpException("Content is empty");
        }

        $content = json_decode($content);
        $dealerName = $content->dealerName;
        $vendorName = $content->vendorName;
        $vendorRole = $content->vendorRole;
        $phone = $content->phone;
        $email = $content->email;
        $howArrivedHere = $content->howArrivedHere;
        try{
            $this->getApplicationService()->createApplication($dealerName, $vendorName, $vendorRole, $phone, $email, $howArrivedHere);
            return new JsonResponse(new ApiResponse(0, "OK"));
        }catch(Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }


    private function getApplicationService() : DealerApplicationService
    {
        return $this->get("DealerApplicationService");
    }
}