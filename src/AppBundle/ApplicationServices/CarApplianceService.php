<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 26/08/16
 * Time: 18:29
 */

namespace AppBundle\ApplicationServices;


use AppBundle\DomainServices\ApplianceOfferDomainService;
use AppBundle\DomainServices\AttachmentDomainService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\CarApplianceDomainService;
use AppBundle\DomainServices\ClientDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\OfferMessageDomainService;
use AppBundle\Entity\Client;
use AppBundle\Entity\Dealer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class CarApplianceService
{
    /**
     * @var CarApplianceDomainService
     */
    private $carApplianceDomainService;

    /**
     * @var ClientDomainService
     */
    private $clientDomainService;

    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var ApplianceOfferDomainService
     */
    private $applianceOfferDomainService;
    /**
     * @var OfferMessageDomainService
     */
    private $offerMessageDomainService;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;
    /**
     * @var int
     */
    private $dealerDistanceInKm;

    /**
     * @var AttachmentDomainService
     */
    private $attachmentDomainService;

    public function __construct(CarApplianceDomainService $carApplianceDomainService, ClientDomainService $clientDomainService, DealerDomainService $dealerDomainService, ApplianceOfferDomainService $applianceOfferDomainService, OfferMessageDomainService $offerMessageDomainService, AvatarDomainService $avatarDomainService, AttachmentDomainService $attachmentDomainService, int $dealerDistanceInKm)
    {
        $this->carApplianceDomainService = $carApplianceDomainService;
        $this->clientDomainService = $clientDomainService;
        $this->applianceOfferDomainService = $applianceOfferDomainService;
        $this->dealerDomainService = $dealerDomainService;
        $this->offerMessageDomainService = $offerMessageDomainService;
        $this->dealerDistanceInKm = $dealerDistanceInKm;
        $this->avatarDomainService = $avatarDomainService;
        $this->attachmentDomainService = $attachmentDomainService;
    }

    public function setDistanceInKm(int $distance)
    {
        $this->dealerDistanceInKm = $distance;
    }

    public function getAppliancesForClient(string $clientId)
    {
        $result = array();
        $appliances = $this->carApplianceDomainService->getAppliancesForClient($clientId);
        foreach ($appliances as $appliance) {
            array_push($result, $appliance->toDTO());
        }
        return $result;
    }

    public function createAppliance(string $clientId, int $vehicleId, string $brand, string $model, array $extras, int $packageId = null, int $color = null)
    {
        $appliance = $this->carApplianceDomainService->createAppliance($clientId, $vehicleId, $brand, $model, $extras, $packageId, $color);
        $client = $this->clientDomainService->findById($clientId);
        $dealerIds = null;
        if ($this->dealerDistanceInKm > 0) {
            $squarePosition = $client->getPosition()->getSquareCoordinates($this->dealerDistanceInKm);
            $dealerIds = $this->dealerDomainService->findDealerIdsByModelInPosition($brand, $model, $squarePosition[0], $squarePosition[1]);
        } else {
            $dealerIds = $this->dealerDomainService->findDealerIdsByModel($brand, $model);
        }
        $this->applianceOfferDomainService->createOffersForCarAppliance($dealerIds, $appliance->getId());
    }

    public function deleteAppliance(string $applianceId)
    {
        $this->carApplianceDomainService->delete($applianceId);
    }

    public function addMessageAsDealer($userId, string $offerId, string $message, UploadedFile $attachment = null)
    {
        $message = $this->offerMessageDomainService->addDealerMessage($offerId, $userId, $message);
        $this->applianceOfferDomainService->markApplianceOfferAsReplied($offerId);
        if($attachment) {
            $this->attachmentDomainService->createAttachmentFromUploadFile($attachment, $message->getId());
        }
    }

    public function addMessageAsClient($userId, string $offerId, string $message, UploadedFile $attachment = null)
    {
        $message = $this->offerMessageDomainService->addClientMessage($offerId, $userId, $message);
        $this->applianceOfferDomainService->markApplianceOfferAsNewMessage($offerId);
        if($attachment) {
            $this->attachmentDomainService->createAttachmentFromUploadFile($attachment, $message->getId());
        }
    }

    public function getThread(string $offerId, string $viewAs)
    {
        $messages = $this->offerMessageDomainService->getThreadByOffer($offerId);

        $applianceOffer = $this->applianceOfferDomainService->findApplianceOfferById($offerId);
        $avatar = $this->avatarDomainService->getAvatarByDealerId($applianceOffer->getDealerId());
        if ($viewAs == "client") {
            $dealer = $this->dealerDomainService->getDealerById($applianceOffer->getDealerId());
            $chatWith = $dealer->getVendorName();
        } else {
            $applianceId = $applianceOffer->getApplianceId();
            $appliance = $this->carApplianceDomainService->getApplianceById($applianceId);
            $user = $this->clientDomainService->findById($appliance->getClientId());
            $chatWith = $user->getName();
        }
        $chatMessages = array();
        foreach ($messages as $message) {
            $messageDTO = $message->toMessageDTO($viewAs);
            $attachment = $this->attachmentDomainService->getAttachmentForMessageId($message->getId());
            if($attachment) {
                $attachmentDTO = $attachment->toDTO();
                $fullUrl = "/attachment/".$attachmentDTO->label;
                $attachmentDTO->label = explode("_", $attachmentDTO->label)[1];
                $attachmentDTO->url = $fullUrl;
                $messageDTO->addAttachment($attachmentDTO);
            }
            if ($message->isDealer() && !is_null($avatar)) {
                $messageDTO->addImage($avatar->toDTO());
            }
            array_push($chatMessages, $messageDTO);
        }

        $returnResponse = array(
            "chatWith" => $chatWith,
            "messages" => $chatMessages
        );
        return $returnResponse;
    }

}