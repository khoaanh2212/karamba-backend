<?php


use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\Entity\Avatar;
use AppBundle\Registry\AvatarRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarDomainServiceTest extends PHPUnit_Framework_TestCase
{
    const DEALER_ID = "dealerId";
    /**
     * @var AvatarDomainService
     */
    private $sut;

    /**
     * @var AvatarRegistry
     */
    private $dummyRegistry;

    /**
     * @var UploadedFile
     */
    private $dummyFile;

    protected function setUp()
    {
        $this->dummyRegistry = $this->getMockBuilder("AppBundle\\Registry\\AvatarRegistry")->disableOriginalConstructor()
            ->setMethods(array("saveOrUpdate", "findOneByDealerId", "findByDealerIds"))
            ->getMock();
        $this->dummyFile = $this->getMockBuilder("Symfony\\Component\\HttpFoundation\\File\\UploadedFile")->disableOriginalConstructor()->getMock();
        $this->sut = new AvatarDomainService($this->dummyRegistry);
    }

    public function test_createAvatarFromUploadFileWillCallToRegistrySaveOrUpdate()
    {
        $this->dummyRegistry->expects($this->once())->method("saveOrUpdate");
        $this->sut->createAvatarFromUploadFile($this->dummyFile, self::DEALER_ID);
    }

    public function test_getAvatarByDealerId_shouldCallRegistryFindOneByDealerId()
    {
        $this->dummyRegistry->expects($this->once())->method("findOneByDealerId")->with(self::DEALER_ID);
        $this->exerciseGetAvatar();
    }

    public function test_getAvatarByDealerId_shouldReturnAvatar()
    {
        $avatar = new Avatar(self::DEALER_ID);
        $this->dummyRegistry->expects($this->any())->method("findOneByDealerId")->will($this->returnValue($avatar));
        $actual = $this->exerciseGetAvatar();
        $this->assertEquals($avatar, $actual);
    }

    public function test_findAllByDealerIds_callToRegistryfindByDealerIds()
    {
        $dealerIds = array("dealer1", "dealer2");
        $this->dummyRegistry->expects($this->once())
            ->method("findByDealerIds")
            ->with($dealerIds);
        $this->sut->findAllByDealerIds($dealerIds);
    }

    private function exerciseGetAvatar()
    {
        return $this->sut->getAvatarByDealerId(self::DEALER_ID);
    }
}