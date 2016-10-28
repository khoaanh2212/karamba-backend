<?php

use AppBundle\Entity\DealerBackgroundImage;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class DealerBackgroundImageRegistryTest extends BaseRegistryTest
{

    const DEALER_ID = "DEALER_ID";

    protected function getEntities()
    {
        return array(
            $this->getImageByDealerId(self::DEALER_ID),
            $this->getImageByDealerId("another")
        );
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }

    protected function getEntity()
    {
        return $this->getImageByDealerId(self::DEALER_ID);
    }

    protected function updateEntity($entity)
    {
        $entity->setImageName("another");
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("DealerBackgroundImageRegistry");
    }

    /**
     * @return DealerBackgroundImage
     */
    private function getImageByDealerId($dealerId)
    {
        $backgroundImage = new DealerBackgroundImage($dealerId);
        $backgroundImage->setImageName("image");
        return $backgroundImage;
    }
}