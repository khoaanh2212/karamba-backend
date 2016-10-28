<?php
namespace AppBundle\DomainServices;

use Doctrine\ORM\EntityNotFoundException;
use AppBundle\Entity\AcceptedDealerApplication;
use AppBundle\Entity\DealerApplication;
use AppBundle\Entity\PendingDealerApplication;
use AppBundle\Registry\DealerApplicationRegistry;

class DealerApplicationDomainService
{
    /**
     * @var DealerApplicationRegistry
     */
    private $registry;

    public function __construct(DealerApplicationRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return PendingDealerApplication[]
     */
    public function listAllPendingApplications() : array
    {
        return $this->registry->findAllPending();
    }
    /**
     * @throws EntityNotFoundException
     */
    public function rejectApplication(string $id)
    {
        $pendingApplication = $this->registry->findOneById($id);
        if(!$pendingApplication) {
            throw new EntityNotFoundException("unexisting application[".$id."]");
        }
        $rejected = $pendingApplication->reject();
        return $this->registry->saveOrUpdate($rejected);
    }

    public function acceptApplication(string $id)
    {
        $pendingApplication = $this->registry->findOneById($id);
        $accepted = $pendingApplication->accept();
        return $this->registry->saveOrUpdate($accepted);
    }

    public function processApplication(AcceptedDealerApplication $acceptedApplication)
    {
        $processed = $acceptedApplication->process();
        return $this->registry->saveOrUpdate($processed);
    }

    public function createApplication(string $dealerName, string $vendorName, string $vendorRole, string $phone, string $email, string $howArrivedHere)
    {
        $pendingApplication = DealerApplication::constructPendingApplication($dealerName, $vendorName, $vendorRole, $phone, $email, $howArrivedHere);
        $this->registry->saveOrUpdate($pendingApplication);
    }

    public function retrieveApplicationAndValidate(string $token): AcceptedDealerApplication
    {
        $acceptedApplication = $this->getAcceptedApplicationByToken($token);
        $acceptedApplication->checkValidToken();
        return $acceptedApplication;
    }

    /**
     * @param string $token
     * @return AcceptedDealerApplication
     * @throws EntityNotFoundException
     */
    private function getAcceptedApplicationByToken(string $token): AcceptedDealerApplication
    {
        $acceptedApplication = $this->registry->findOneByToken($token);
        if(!$acceptedApplication) {
            throw new EntityNotFoundException;
        }
        return $acceptedApplication;
    }
}