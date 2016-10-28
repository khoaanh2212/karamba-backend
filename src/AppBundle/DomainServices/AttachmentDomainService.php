<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 11/10/16
 * Time: 15:43
 */

namespace AppBundle\DomainServices;


use AppBundle\Entity\OfferMessageFile;
use AppBundle\Registry\OfferMessageFileRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentDomainService
{
    /**
     * @var OfferMessageFileRegistry
     */
    private $registry;

    public function __construct(OfferMessageFileRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function createAttachmentFromUploadFile(UploadedFile $attachment, string $messageId)
    {
        $offerMessageFile = $this->registry->findOneByMessageId($messageId);
        if(!$offerMessageFile) {
            $offerMessageFile = new OfferMessageFile($messageId);
            $this->registry->saveOrUpdate($offerMessageFile);
        }
        $offerMessageFile->setImageFile($attachment);
        $this->registry->saveOrUpdate($offerMessageFile);
    }

    public function getAttachmentForMessageId(string $messageId)
    {
        return $this->registry->findOneByMessageId($messageId);
    }
}