<?php

namespace AppBundle\Entity;

use AppBundle\DTO\ApplianceDetailDTO;
use AppBundle\DTO\CarApplianceDTO;
use AppBundle\DTO\ExtrasDTO;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Utils\UUIDGeneratorFactory;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\CarApplianceRepository")
* @ORM\Table(name="carappliances")
*/
class CarAppliance implements ISerializableDTO
{
    const MAX_NUMBER_OF_OFFERS = 5;

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
    private $clientId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $derivative;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $transmission;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $motorType;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $numberOfDoors;

    /**
     * @var int
     * @ORM\Column(type="bigint")
     */
    private $vehicleId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var string[]
     * @ORM\Column(type="json_array")
     */
    private $extras;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="json_array")
     */
    private $package;

    /**
     * @ORM\Column(type="json_array")
     */
    private $color;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $numberOfOffers;

    public function __construct(string $clientId,int $vehicleId, string $brand, string $model, string $derivative, int $numberOfDoors, $transmission, $motorType, float $price, string $image, array $extras = null, ExtrasDTO $package = null, ExtrasDTO $color = null)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->clientId = $clientId;
        $this->vehicleId = $vehicleId;
        $this->brand = $brand;
        $this->model = $model;
        $this->price = $price;
        $this->package = $package;
        $this->extras = $extras;
        $this->color = $color;
        $this->numberOfOffers = 0;
        $this->image = $image;
        $this->derivative = $derivative;
        $this->numberOfDoors = $numberOfDoors;
        $this->transmission = $transmission;
        $this->motorType = $motorType;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function addOffer()
    {
        $this->numberOfOffers++;
    }

    public function isAvailableForOffers(): bool
    {
        if($this->numberOfOffers < self::MAX_NUMBER_OF_OFFERS) {
            return true;
        }
        return false;
    }

    public function toDTO()
    {
        return new CarApplianceDTO($this->id, $this->brand, $this->model, $this->image, $this->extras, $this->numberOfOffers, $this->package, $this->color);
    }

    public function toDetailDTO()
    {
        return new ApplianceDetailDTO($this->id, $this->brand, $this->model, $this->derivative, $this->price, $this->motorType, $this->transmission, $this->numberOfDoors, $this->package, $this->extras, $this->color);
    }
}