<?php

namespace AppBundle\ApplicationServices;


use AppBundle\DomainServices\DealerApplicationDomainService;
use AppBundle\DTO\DealerApplicationDTO;
use AppBundle\Entity\DealerConfirmationMail;

class DealerApplicationService
{
    /**
     * @var DealerApplicationDomainService
     */
    private $dealerApplicationDomainService;

    /**
     * @var DealerConfirmationMail
     */
    private $mailer;

    public function __construct(DealerApplicationDomainService $dealerApplicationDomainService, DealerConfirmationMail $mailer)
    {
        $this->dealerApplicationDomainService = $dealerApplicationDomainService;
        $this->mailer = $mailer;
    }

    /**
     * @return DealerApplicationDTO[]
     */
    public function listAllPendingApplications(): array
    {
        $dtoData = array();
        $actual = $this->dealerApplicationDomainService->listAllPendingApplications();
        foreach ($actual as $pendingDealerApplication) {
            array_push($dtoData, $pendingDealerApplication->toDTO());
        }
        return $dtoData;
    }

    public function rejectApplication(string $id)
    {
        $this->dealerApplicationDomainService->rejectApplication($id);
    }

    public function acceptApplication(string $id)
    {
        $accepted = $this->dealerApplicationDomainService->acceptApplication($id);
        $this->mailer->send($accepted->getMail(), $accepted->getName(), $accepted->getVendorName(), $accepted->getToken());
        return $accepted->toTokenDTO();
    }

    public function createApplication(string $dealerName, string $vendorName, string $vendorRole, string $phone, string $email, string $howArrivedHere)
    {
        $this->dealerApplicationDomainService->createApplication($dealerName, $vendorName, $vendorRole, $phone, $email, $howArrivedHere);
    }

    public function retrieveApplicationAndValidate(string $token): DealerApplicationDTO
    {
        $acceptedDealerApplication = $this->dealerApplicationDomainService->retrieveApplicationAndValidate($token);
        return $acceptedDealerApplication->toDTO();
    }
}