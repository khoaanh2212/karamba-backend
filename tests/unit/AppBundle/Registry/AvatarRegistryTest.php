<?php


use AppBundle\Entity\Avatar;
use AppBundle\Utils\UUIDGeneratorFactory;

require_once __DIR__ . '/../../utils/BaseRegistryTest.php';

class AvatarRegistryTest extends BaseRegistryTest
{

    const DEALER_ID = "DEALER_ID";

    protected function getEntities()
    {
        return array(
            $this->getAvatarByDealerID(self::DEALER_ID),
            $this->getAvatarByDealerID("another")
        );
    }

    protected function getEntity()
    {
        $avatar = $this->getAvatarByDealerID(self::DEALER_ID);
        return $avatar;
    }

    protected function updateEntity($entity)
    {
        $entity->setImageName("another");
    }

    public function test_saveOrUpdate_existingEntity_shouldUpdateTheEntity()
    {
        $this->markTestSkipped();
    }

    public function test_findByDealerIds_shouldReturnAllAvatars()
    {
        UUIDGeneratorFactory::reset();
        $dealerId = "dealer1";
        $dealerId2 = "dealer2";
        $dealerId3 = "dealer3";
        $avatar1 = $this->getAvatarByDealerID($dealerId);
        $avatar2 = $this->getAvatarByDealerID($dealerId2);
        $avatar3 = $this->getAvatarByDealerID($dealerId3);
        $this->sut->saveOrUpdate($avatar1);
        $this->sut->saveOrUpdate($avatar2);
        $this->sut->saveOrUpdate($avatar3);
        $actual = $this->sut->findByDealerIds(array($dealerId, $dealerId2));
        $this->assertEquals(2, count($actual));
    }

    protected function getSut() : \AppBundle\Utils\RegistryBase
    {
        return static::$kernel->getContainer()->get("AvatarRegistry");
    }

    /**
     * @return Avatar
     */
    protected function getAvatarByDealerID($dealerId)
    {
        $avatar = new Avatar($dealerId);
        $avatar->setImageName("test-image");
        return $avatar;
    }
}