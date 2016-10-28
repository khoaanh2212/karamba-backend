<?php


use AppBundle\Entity\DealerApplication;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class AcceptedDealerRegistryTest extends BaseRegistryTest
{

    protected function getEntities()
    {
        $return = array();
        array_push($return, $this->getAcceptedApplication("mail1"));
        array_push($return, $this->getAcceptedApplication("mail2"));
        array_push($return, $this->getAcceptedApplication("mail3"));
        array_push($return, $this->getAcceptedApplication("mail4"));
        return $return;
    }

    public function test_findAllAccepted_willReturnAcceptedApplications()
    {
        UUIDGeneratorFactory::reset();
        $toPersist = array();
        array_push($toPersist, $this->getAcceptedApplication("mail1"));
        array_push($toPersist, $this->constructPendingApplication("mail2"));
        array_push($toPersist, $this->constructPendingApplication("mail3"));
        array_push($toPersist, $this->constructPendingApplication("mail4"));
        $this->persistRecords($toPersist);
        $actual = $this->sut->findAllAccepted();
        $this->assertEquals(1, count($actual));
    }

    public function test_findOneByToken_willReturnCorrectApplication()
    {
        $entity = $this->persistAndRetrievePassedEntity($this->getEntity());
        $retrieved = $this->sut->findOneByToken(self::TEST_ID);
        $this->assertEquals($entity, $retrieved);
    }

    protected function getEntity()
    {
        return $this->getAcceptedApplication();
    }

    protected function updateEntity($entity)
    {
        $entity->setVendorName("another");
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("DealerApplicationRegistry");
    }


    private function getAcceptedApplication($email = null)
    {
        if(!$email) {
            $email = "email";
        }
        return DealerApplication::constructAcceptedApplication("vendor name", "dealer name", "vendor role", "phone", $email, "how", "token", new \DateTime());
    }

    /**
     * @return \AppBundle\Entity\PendingDealerApplication
     */
    private function constructPendingApplication($email = null)
    {
        if(!$email) {
            $email = "email";
        }
        return DealerApplication::constructPendingApplication("vendor name", "dealer name", "vendor role", "phone", $email, "how");
    }
}