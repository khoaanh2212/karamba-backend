<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 11/10/16
 * Time: 15:45
 */

namespace unit\AppBundle\DomainServices;
use AppBundle\DomainServices\AttachmentDomainService;
use AppBundle\Entity\OfferMessageFile;
use AppBundle\Registry\OfferMessageFileRegistry;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const MESSAGE_ID = "messageID";
    /**
     * @var AttachmentDomainService
     */
    private $sut;

    /**
     * @var OfferMessageFileRegistry
     */
    private $registry;

    /**
     * @var UploadedFile
     */
    private $dummyFile;

    /**
     * @var OfferMessageFile
     */
    private $dummyOfferMessageFile;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\OfferMessageFileRegistry")
                                ->disableOriginalConstructor()
                                ->setMethods(array("findOneByMessageId", "saveOrUpdate"))
                                ->getMock();
        $this->dummyFile = $this->getMockBuilder("Symfony\\Component\\HttpFoundation\\File\\UploadedFile")->disableOriginalConstructor()->getMock();
        $this->dummyOfferMessageFile = $this->getMockBuilder("AppBundle\\Entity\\OfferMessageFile")->disableOriginalConstructor()->getMock();
        $this->sut = new AttachmentDomainService($this->registry);
    }

    public function test_createAttachmentFromUploadFile_callFindOneByMessageId()
    {
        $this->registry->expects($this->once())->method('findOneByMessageId', self::MESSAGE_ID)->will($this->returnValue($this->dummyOfferMessageFile));
        $this->sut->createAttachmentFromUploadFile($this->dummyFile, self::MESSAGE_ID);
    }

    public function test_createAttachmentFromUploadFile_AndFileIsPresentShouldCallToSetAttachmentWithTheFile()
    {
        $this->dummyOfferMessageFile->expects($this->once())->method("setImageFile")->with($this->dummyFile);
        $this->registry->expects($this->any())->method('findOneByMessageId')->will($this->returnValue($this->dummyOfferMessageFile));
        $this->sut->createAttachmentFromUploadFile($this->dummyFile, self::MESSAGE_ID);
    }

    public function test_createAttachmentFromUploadFile_AndFileIsPresentShouldCallToRegistrySaveOrUpdateWithTheOfferMessageFile()
    {
        $this->registry->expects($this->any())->method('findOneByMessageId')->will($this->returnValue($this->dummyOfferMessageFile));
        $this->registry->expects($this->once())->method('saveOrUpdate')->with($this->dummyOfferMessageFile);
        $this->sut->createAttachmentFromUploadFile($this->dummyFile, self::MESSAGE_ID);
    }

    public function test_getAttachmentForMessageCalledWithMessageIdWillCallToRegistryFindOneByMessageId()
    {
        $this->registry->expects($this->once())->method("findOneByMessageId");
        $this->sut->getAttachmentForMessageId(self::MESSAGE_ID);
    }

}