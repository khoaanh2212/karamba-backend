<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 26/10/16
 * Time: 12:38
 */

namespace AppBundle\DomainServices;


use AppBundle\Utils\EmailSender;

class ReviewMailerDomainService
{
    const SUBJECT = "Gracias por la valoración del concesionario";
    const TEMPLATE_PATH = 'default/review.html.twig';

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
    private $address;

    public function __construct(EmailSender $sender,  string $senderAddress)
    {
        $this->mailSender = $sender;
        $this->senderAddress = $senderAddress;
    }

    public function send(string $email, string $clientName, string $vendorName, string $gift)
    {
        $this->mailSender->setSubject(self::SUBJECT);
        $this->mailSender->setSender($this->senderAddress);
        $this->mailSender->setReceptor($email);
        $this->mailSender->setBodyFromTemplate(self::TEMPLATE_PATH, array(
            "clientName" => $clientName,
            "vendorName" => $vendorName,
            "gift" => $gift
        ));
        $this->mailSender->send();
    }
}