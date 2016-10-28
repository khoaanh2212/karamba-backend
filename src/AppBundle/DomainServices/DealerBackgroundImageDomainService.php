<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 28/07/16
 * Time: 16:41
 */

namespace AppBundle\DomainServices;

use AppBundle\Entity\DealerBackgroundImage;
use AppBundle\Registry\DealerBackgroundImageRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DealerBackgroundImageDomainService
{
    /**
     * @var DealerBackgroundImageRegistry
     */
    private $registry;

    public function __construct(DealerBackgroundImageRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function createBackgroundImageFromUploadFile(UploadedFile $file, string $dealerId)
    {
        $image = $this->registry->findOneByDealerId($dealerId);
        if(!$image) {
            $image = new DealerBackgroundImage($dealerId);
        }
        $image->setImageFile($file);
        $this->registry->saveOrUpdate($image);
    }

    public function getBackgroundImageByDealerId(string $dealerId)
    {
        return $this->registry->findOneByDealerId($dealerId);
    }
}