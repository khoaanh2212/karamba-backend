<?php

namespace AppBundle\Entity;
use AppBundle\DTO\ReviewDetailDTO;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Utils\UUIDGeneratorFactory;

/**
 * Class ReviewDetail
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReviewDetailRepository")
 * @ORM\Table(name="reviewdetails")
 */
class ReviewDetail implements ISerializableDTO
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
    private $reviewId;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $rating;

    public function __construct(string $reviewId, string $type, float $rating) {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->reviewId = $reviewId;
        $this->type = $type;
        $this->rating = $rating;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getType() {
        return $this->type;
    }

    public function toDTO()
    {
        return new ReviewDetailDTO($this->type, $this->rating);
    }
}