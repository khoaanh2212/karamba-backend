<?php

use AppBundle\Entity\DealerApplication;
use AppBundle\Utils\RegistryBase;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class PendingDealerRegistryTest extends BaseRegistryTest
{

    protected function getEntities()
    {
        $return = array();
        array_push($return, $this->constructPendingApplication("mail1"));
        array_push($return, $this->constructPendingApplication("mail2"));
        array_push($return, $this->constructPendingApplication("mail3"));
        array_push($return, $this->constructPendingApplication("mail4"));
        return $return;
    }


    /**
     * @expectedException \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function test_saveOrUpdate_sameEmail_shouldRaiseException()
    {
        UUIDGeneratorFactory::reset();
        $first = $this->constructPendingApplication();
        $second = $this->constructPendingApplication();
        $this->persistAndRetrievePassedEntity($first);
        $this->persistAndRetrievePassedEntity($second);
    }

    public function test_findAll_will_returnPendingApplications()
    {
        UUIDGeneratorFactory::reset();
        $toPersist = array();
        array_push($toPersist, $this->constructPendingApplication("mail1"));
        array_push($toPersist, $this->constructPendingApplication("mail2"));
        array_push($toPersist, $this->constructPendingApplication("mail3"));
        array_push($toPersist, $this->getAcceptedApplication("mail4"));
        $this->persistRecords($toPersist);
        $actual = $this->sut->findAllPending();
        $this->assertEquals(3, count($actual));
    }

    protected function getEntity()
    {
        return $this->constructPendingApplication();
    }


    protected function updateEntity($entity)
    {
        $entity->setVendorName("another");
    }

    protected function getSut() : RegistryBase
    {
        return static::$kernel->getContainer()->get("DealerApplicationRegistry");
    }

    /**
     * @return \AppBundle\Entity\PendingDealerApplication
     */
    protected function constructPendingApplication($email = null)
    {
        if(!$email) {
            $email = "email";
        }
        return DealerApplication::constructPendingApplication("vendor name", "dealer name", "vendor role", "phone", $email, "how");
    }

    private function getAcceptedApplication($email = null)
    {
        if(!$email) {
            $email = "email";
        }
        return DealerApplication::constructAcceptedApplication("vendor name", "dealer name", "vendor role", "phone", $email, "how", "token", new \DateTime());
    }
}