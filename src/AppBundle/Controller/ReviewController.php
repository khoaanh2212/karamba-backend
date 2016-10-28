<?php
/**
 * Created by PhpStorm.
 * User: khoaanh
 * Date: 20/10/2016
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\ApplicationServices\ReviewService;
use AppBundle\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    /**
     * @Route("/api/client/review")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function createReview(Request $request)
    {
        try {
            $jsonRequest = json_decode($request->getContent());
            $dealerId = $jsonRequest->dealerId;
            $clientId = $this->getUser()->getId();
            $giftId = $jsonRequest->giftId;
            $reviewerFullName = $jsonRequest->reviewerFullName;
            $reviewerBusinessName = $jsonRequest->reviewerBusinessName;
            $comment = $jsonRequest->comment;
            $ratings = $jsonRequest->ratings;
            $this->getReviewService()->createReview($dealerId, $clientId, $giftId, $reviewerFullName, $reviewerBusinessName, $comment, $ratings);
            return new JsonResponse(new ApiResponse(0, null));
        } catch (\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/dealer/review/{id}")
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function acceptReview($id)
    {
        try{
            return new JsonResponse(new ApiResponse(0, $this->getReviewService()->acceptReview($id)));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    /**
     * @Route("/api/dealer/review/{id}")
     * @Method({"DELETE"})
     * @return JsonResponse
     */
    public function rejectReview($id)
    {
        try{
            return new JsonResponse(new ApiResponse(0, $this->getReviewService()->rejectReview($id)));
        }catch(\Exception $e) {
            return new JsonResponse(new ApiResponse($e->getCode(), "ERROR"));
        }
    }

    private function getReviewService(): ReviewService
    {
        return $this->get("ReviewService");
    }
}
