<?php


use AppBundle\Entity\DealerConfirmationMail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Utils\EmailSender;

class DealerConfirmationMailTest extends KernelTestCase
{
    private $senderAddress = "senderAddress";
    const EMAIL = "email";
    const NAME = "name";
    const VENDOR_NAME = "vendorName";
    const TOKEN = "token";
    const SUBJECT = "Solicitud de concesionario accceptada";
    const ENDPOINT = "api.carnovo.com/acceptedapplications";
    /**
     * @var DealerConfirmationMail
     */
    private $sut;

    /**
     * @var EmailSender
     */
    private $dummyMailSender;

    protected function setUp()
    {
        $this->dummyMailSender = $this->getMockBuilder("AppBundle\\Utils\\EmailSender")->disableOriginalConstructor()->getMock();
        $this->sut = new DealerConfirmationMail($this->dummyMailSender, $this->senderAddress, self::ENDPOINT);
    }

    public function test_send_withData_shouldCallEmailSenderSetSubject()
    {
        $this->dummyMailSender->expects($this->once())
                ->method("setSubject")
                ->with(self::SUBJECT);
        $this->exerciseSend();
    }

    public function test_send_withData_shouldCallEmailSenderSetSender()
    {
        $this->dummyMailSender->expects($this->once())->method("setSender")->with($this->senderAddress);
        $this->exerciseSend();
    }

    public function test_send_withData_shouldCallEmailSetReceptorWithTheEmail()
    {
        $this->dummyMailSender->expects($this->once())->method("setReceptor")->with(self::EMAIL);
        $this->exerciseSend();
    }

    public function test_send_withData_shouldCallEmailsetBodyFromTemplateWithCorrectData()
    {
        $this->dummyMailSender->expects($this->once())->method("setBodyFromTemplate")->with('default/email.html.twig',array(
            "vendorName" => self::VENDOR_NAME,
            "dealerName" => self::NAME,
            "maillink" => self::ENDPOINT."/".self::TOKEN
        ));
        $this->exerciseSend();
    }

    public function test_send_withData_shouldCallEmailSend()
    {
        $this->dummyMailSender->expects($this->once())->method("send");
        $this->exerciseSend();
    }

    public function test_exploratory()
    {
        $this->markTestSkipped();
        self::bootKernel();
        $alternate = new DealerConfirmationMail(static::$kernel->getContainer()->get("EmailSender"), "info@carnovo.com", "carnovo.com/token");
        $alternate->send("sergi.fernandez@apiumtech.com", "name","vendor name", "some token");
    }

    private function exerciseSend()
    {
        $this->sut->send(self::EMAIL, self::NAME, self::VENDOR_NAME, self::TOKEN);
    }
}