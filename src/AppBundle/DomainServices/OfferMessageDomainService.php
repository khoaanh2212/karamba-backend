<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 31/08/16
 * Time: 10:20
 */

namespace AppBundle\DomainServices;

use AppBundle\Entity\OfferMessage;
use AppBundle\Registry\OfferMessageRegistry;

class OfferMessageDomainService
{
    const DEALER = "dealer";
    const CLIENT = "client";
    
    /**
     * @var OfferMessageRegistry
     */
    private $registry;

    public function __construct(OfferMessageRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function addDealerMessage(string $offerId, string $authorId, $message): OfferMessage
    {
        $message = new OfferMessage($offerId, $authorId, self::DEALER, $message);
        return $this->registry->saveOrUpdate($message);
    }

    public function addClientMessage(string $offerId, string $authorId, $message): OfferMessage
    {
        $message = new OfferMessage($offerId, $authorId, self::CLIENT, $message);
        return $this->registry->saveOrUpdate($message);
    }

    public function getThreadByOffer(string $offerId)
    {
        return $this->registry->findBy(array('offerId' => $offerId), array('created' => 'ASC'));
    }
}