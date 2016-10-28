<?php

namespace AppBundle\Entity;

use AppBundle\DTO\ReviewDTO;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Utils\UUIDGeneratorFactory;
use AppBundle\Utils\SystemClock;

/**
 * Class Review
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReviewRepository")
 * @ORM\Table(name="reviews")
 */
class Review implements ISerializableDTO
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
    private $clientId;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=36)
     */
    private $giftId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $reviewerFullName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $reviewerBusinessName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $comment;

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

    const PENDING = "pending";
    const ACCEPTED = "accepted";
    const REJECTED = "rejected";

    public function __construct(string $dealerId, string $clientId, string $giftId, string $reviewerFullName = null, string $reviewerBusinessName = null, string $comment = null) {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->dealerId = $dealerId;
        $this->clientId = $clientId;
        $this->giftId = $giftId;
        $this->reviewerFullName = $reviewerFullName;
        $this->reviewerBusinessName = $reviewerBusinessName;
        $this->comment = $comment;
        $this->created = SystemClock::now()->getDate();
        $this->state =  self::PENDING;
    }

    public function getId() {
        return $this->id;
    }

    public function accept()
    {
        $this->state = self::ACCEPTED;
    }

    public function reject()
    {
        $this->state = self::REJECTED;
    }

    public function toDTO()
    {
        return new ReviewDTO($this->id, $this->reviewerFullName, $this->reviewerBusinessName, $this->comment, $this->created, $this->state);
    }
}