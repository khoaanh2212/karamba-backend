<?php
namespace AppBundle\DomainServices;


use AppBundle\Utils\EmailSender;

class OfferMailerDomainService
{
    const SUBJECT = "Nueva oferta recibida";
    const TEMPLATE_PATH = 'default/offer.html.twig';

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

    public function __construct(EmailSender $sender,  string $senderAddress, string $address)
    {
        $this->mailSender = $sender;
        $this->senderAddress = $senderAddress;
        $this->address = $address;
    }

    public function send(string $offerId, string $email, string $clientName, string $vendorName, string $carBrand, string $carModel, string $message)
    {
        $this->mailSender->setSubject(self::SUBJECT);
        $this->mailSender->setSender($this->senderAddress);
        $this->mailSender->setReceptor($email);
        $this->mailSender->setBodyFromTemplate(self::TEMPLATE_PATH, array(
            "clientName" => $clientName,
            "vendorName" => $vendorName,
            "carBrand" => $carBrand,
            "carModel" => $carModel,
            "message" => $message,
            "url" => $this->address.$offerId."/dealer/"
        ));
        $this->mailSender->send();
    }
}