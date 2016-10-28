<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 8/07/16
 * Time: 15:08
 */

namespace AppBundle\Utils;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

use Symfony\Component\Config\Definition\Exception\Exception;
use Twig_Environment;

class EmailSender
{

    const TEMPLATE_PATH = 'Emails/';

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Swift_SmtpTransport
     */
    private $transport;

    /**
     * @var Swift_Message
     */

    private $message;
    /**
     * @var Twig_Environment
     */
    private $twig;


    public function __construct(Twig_Environment $twig, Swift_Mailer $mailer)
    {
        $this->mailer  = $mailer;
        $this->message = $mailer->createMessage();
        $this->twig    = $twig;

    }

    public function setSubject(string $subject)
    {
        $this->message->setSubject($subject);
    }

    public function setSender(string $sender)
    {
        $this->message->setFrom($sender);
    }

    public function setReceptor(string $receptor)
    {
        $this->message->setTo($receptor);
    }

    public function setBodyFromTemplate(string $templateName, array $arrayParams)
    {
        $this->message->setBody($this->twig->render(
            $templateName,
            $arrayParams), "text/html");
    }

    public function send()
    {
        $this->validateEmail();
        return $this->mailer->send($this->message);

    }

    private function validateEmail()
    {
        if ($this->message->getTo() === null ||
            $this->message->getFrom() === null ||
            $this->message->getSubject() === null
        ) {
            throw new Exception('Invalid Email');
        }
    }

}