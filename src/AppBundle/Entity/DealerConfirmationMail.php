<?php

namespace AppBundle\Entity;

use AppBundle\Utils\EmailSender;

class DealerConfirmationMail
{
    const SUBJECT = "Solicitud de concesionario accceptada";
    const TEMPLATE_PATH = 'default/email.html.twig';
    /**
     * @var EmailSender
     */
    private $mailSender;

    /**
     * @var string
     */
    private $senderAddress;

    /**
     * @var string
     */
    private $tokenAddress;

    public function __construct(EmailSender $sender, $senderAddress, $tokenAddress)
    {
        $this->mailSender = $sender;
        $this->senderAddress = $senderAddress;
        $this->tokenAddress = $tokenAddress;
    }

    public function send(string $email, string $name, string $vendorName, string $token)
    {
        $this->mailSender->setSubject(self::SUBJECT);
        $this->mailSender->setSender($this->senderAddress);
        $this->mailSender->setReceptor($email);
        $this->mailSender->setBodyFromTemplate(self::TEMPLATE_PATH, array(
            "vendorName" => $vendorName,
            "dealerName" => $name,
            "maillink" => $this->tokenAddress."/".$token
        ));
        $this->mailSender->send();
    }
}