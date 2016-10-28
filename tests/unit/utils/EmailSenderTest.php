<?php
use AppBundle\Utils\EmailSender;

/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 11/07/16
 * Time: 11:21
 */
class EmailSenderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @EmailSender
     */
    private $sut;

    function test_whenValidTemplate_thenTemplateRendered()
    {
        $twigMock = $this->getMockBuilder('\Twig_Environment')->disableOriginalConstructor()->getMock();
        $twigMock->expects($this->exactly(1))->method('render');

        $swiftMessageMock = $this->getMockBuilder('\Swift_Message')->disableOriginalConstructor()->getMock();
        $swiftMessageMock->expects($this->once())->method('setBody');

        $swiftMailerMock = $this->getSwiftMailerMock($swiftMessageMock);

        $this->sut = new EmailSender($twigMock, $swiftMailerMock);
        $this->sut->setBodyFromTemplate('welcome', array());
    }

    /**
     * @expectedException Exception
     */
    function test_whenInvalidEmail_thenExceptionThrown()
    {
        $swiftMessageMock = $this->getMockBuilder('\Swift_Message')->disableOriginalConstructor()->getMock();
        $swiftMessageMock->expects($this->once())->method('getTo')->willReturn(null);

        $swiftMailerMock = $this->getSwiftMailerMock($swiftMessageMock);

        $this->sut = new EmailSender(new Twig_Environment(new \Twig_Loader_Array(array())), $swiftMailerMock);
        $this->sut->send();
    }

    function test_whenValidEmail_thenSendCalled()
    {
        $swiftMessageMock = $this->getMockBuilder('\Swift_Message')->disableOriginalConstructor()->getMock();

        $swiftMessageMock->expects($this->once())->method('setSubject');
        $swiftMessageMock->expects($this->once())->method('setTo');
        $swiftMessageMock->expects($this->once())->method('setFrom');

        $swiftMessageMock->expects($this->once())->method('getSubject')->willReturn('subject');
        $swiftMessageMock->expects($this->once())->method('getFrom')->willReturn('from_someone@gmail.com');
        $swiftMessageMock->expects($this->once())->method('getTo')->willReturn('to_someone@gmail.com');

        $swiftMailerMock = $this->getSwiftMailerMock($swiftMessageMock);
        $swiftMailerMock->expects($this->exactly(1))->method('send');

        $this->sut = new EmailSender(new Twig_Environment(new \Twig_Loader_Array(array())), $swiftMailerMock);

        $this->sut->setSubject("subject");
        $this->sut->setReceptor("to_someone@gmail.com");
        $this->sut->setSender("from_someone@gmail.com");
        $this->sut->send();
    }

    /**
     * @param $swiftMessageMock
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getSwiftMailerMock($swiftMessageMock)
    {
        $swiftMailerMock = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();

        $swiftMailerMock->expects($this->once())->method('createMessage')->willReturn($swiftMessageMock);

        return $swiftMailerMock;
    }
}
