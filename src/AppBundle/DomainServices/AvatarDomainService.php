<?php

namespace AppBundle\DomainServices;


use AppBundle\Entity\Avatar;
use AppBundle\Registry\AvatarRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarDomainService
{

    /**
     * @var AvatarRegistry
     */
    private $registry;

    public function __construct(AvatarRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function createAvatarFromUploadFile(UploadedFile $file, string $dealerId)
    {
        $avatar = $this->registry->findOneByDealerId($dealerId);
        if(!$avatar) {
            $avatar = new Avatar($dealerId);
        }
        $avatar->setImageFile($file);
        $this->registry->saveOrUpdate($avatar);
    }

    public function findAllByDealerIds(array $dealerIds)
    {
        return $this->registry->findByDealerIds($dealerIds);
    }

    public function getAvatarByDealerId(string $dealerId)
    {
        return $this->registry->findOneByDealerId($dealerId);
    }
}