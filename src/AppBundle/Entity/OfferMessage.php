<?php

namespace AppBundle\Entity;

use AppBundle\DTO\OfferMessageDTO;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGeneratorFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DealerCondition
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferMessageRepository")
 * @ORM\Table(name="offermessages")
 */
class OfferMessage
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
    private $offerId;
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $authorId;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $authorType;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $message;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;


    public function __construct(string $offerId, string $authorId, string $authorType, string $message)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->offerId = $offerId;
        $this->authorId = $authorId;
        $this->authorType = $authorType;
        $this->message = $message;
        $this->created = SystemClock::now()->getDate();
    }
    
    public function getMessage(){
        return $this->message;
    }

    public function getId() {
        return $this->id;
    }

    public function isDealer()
    {
        return $this->authorType == "dealer";
    }
    
    public function toMessageDTO(string $viewAs){
        $type = "receive";
        if($viewAs == $this->authorType) {
            $type = "send";
        }
        return new OfferMessageDTO($type, $this->message, $this->created);
    }

}