<?php


use AppBundle\DomainServices\DealerBackgroundImageDomainService;
use AppBundle\Entity\DealerBackgroundImage;
use AppBundle\Registry\DealerBackgroundImageRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DealerBackgroundImageDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "DEALER_ID";
    /**
     * @var DealerBackgroundImageDomainService
     */
    private $sut;


    /**
     * @var DealerBackgroundImageRegistry
     */
    private $registry;

    /**
     * @var UploadedFile
     */
    private $dummyFile;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\DealerBackgroundImageRegistry")->disableOriginalConstructor()
            ->setMethods(array("saveOrUpdate","findOneByDealerId"))
            ->getMock();
        $this->dummyFile = $this->getMockBuilder("Symfony\\Component\\HttpFoundation\\File\\UploadedFile")->disableOriginalConstructor()->getMock();
        $this->sut = new DealerBackgroundImageDomainService($this->registry);
    }

    public function test_createBackgroundImageFromUploadFileWillCallToRegistrySaveOrUpdate()
    {
        $this->registry->expects($this->once())->method("saveOrUpdate");
        $this->sut->createBackgroundImageFromUploadFile($this->dummyFile, self::DEALER_ID);
    }

    public function test_getBackgroundImageByDealerId_shouldReturnAvatar()
    {
        $image = new DealerBackgroundImage(self::DEALER_ID);
        $this->registry->expects($this->any())->method("findOneByDealerId")->will($this->returnValue($image));
        $actual = $this->exerciseGetBackgroundImage();
        $this->assertEquals($image, $actual);
    }

    private function exerciseGetBackgroundImage()
    {
        return $this->sut->getBackgroundImageByDealerId(self::DEALER_ID);
    }
}