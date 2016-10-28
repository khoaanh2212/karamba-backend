<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 16:11
 */

namespace AppBundle\Entity;

use AppBundle\DTO\ApplianceOfferDTO;
use AppBundle\Utils\ApplianceOfferState;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGeneratorFactory;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApplianceOfferRepository")
 * @ORM\Table(name="applianceOffers")
 */
class ApplianceOffer implements ISerializableDTO
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $dealerId;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $applianceId;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $state;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $cashPrice;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $foundedPrice;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isRead;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $inStock;

    public function getApplianceId()
    {
        return $this->applianceId;
    }

    public function __construct(string $dealerId, string $applianceId)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->dealerId = $dealerId;
        $this->applianceId = $applianceId;
        $this->state = ApplianceOfferState::NEW_OPPORTUNITY;
        $this->created = SystemClock::now()->getDate();
        $this->isRead = false;
    }

    public function makeAnOffer(string $dealerId, float $cashPrice, $foundedPrice, bool $inStock)
    {
        if ($dealerId != $this->dealerId) {
            throw new \SecurityException("Not Authorized dealer[$dealerId]");
        }

        $this->cashPrice = $cashPrice;
        $this->foundedPrice = $foundedPrice;
        $this->inStock = $inStock;
        $this->state = ApplianceOfferState::SENT_OFFER;
    }

    public function markAsNewMessage()
    {
        $this->state = ApplianceOfferState::NEW_MESSAGE;
        $this->isRead = false;
    }

    public function markAsReplied()
    {
        $this->state = ApplianceOfferState::REPLIED;
        $this->isRead = true;
    }

    public function markAsRead()
    {
        $this->isRead = true;
    }

    public function setDealerId($dealerId)
    {
        $this->dealerId = $dealerId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDealerId()
    {
        return $this->dealerId;
    }

    public function getCashPrice()
    {
        return $this->cashPrice;
    }

    public function getFoundedPrice()
    {
        return $this->foundedPrice;
    }

    public function toDTO(bool $isBestPrice = false, bool $isClosest = false, bool $isHighestRating = false, float $distance = -1, $ratings = array())
    {
        return new ApplianceOfferDTO($this->id, $this->dealerId, $this->applianceId, $this->state, $this->cashPrice,
            $this->foundedPrice, $this->inStock, $this->isRead, $this->created, $isBestPrice, $isClosest, $isHighestRating, $distance, $ratings);
    }
}