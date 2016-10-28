<?php

namespace AppBundle\Entity;

use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Utils\UUIDGeneratorFactory;

/**
 * Class StockCars
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockCarRepository")
 * @ORM\Table(name="stockcars")
 */
class StockCar implements ISerializableDTO
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Dealer", cascade={"persist", "remove"})
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $dealerId;

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
    private $year;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $vehicleId;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $fuelType;
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
    private $doors;

    /**
     * @var string
     * @ORM\Column(type="json_array")
     */
    private $color;

    /**
     * @ORM\Column(type="json_array")
     */
    private $package;

    /**
     * @var string[]
     * @ORM\Column(type="json_array")
     */
    private $extras;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $pvp;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $cash;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $discount;


    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $photoUrl;

    private $isNew;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $makeKey;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $modelKey;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $priceToDisplay;

    public function __construct(string $dealerId, VehicleDTO $vehicle, VehicleOptionDTO $color = null, array $extras, Price $price, string $photoUrl, $package = null)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->dealerId = $dealerId;

        $this->brand = $vehicle->makeNameToDisplay;
        $this->model = $vehicle->modelNameToDisplay;
        $this->year = $vehicle->modelYearToDisplay;

        $this->vehicleId = $vehicle->vehicleId;
        $this->fuelType = $vehicle->fuelTypeToDisplay;
        $this->derivative = $vehicle->derivativeToDisplay;
        $this->transmission = $vehicle->transmission;
        $this->doors = $vehicle->numberOfDoorsToDisplay;
        $this->makeKey = $vehicle->makeKey;
        $this->makeKey = $vehicle->makeKey;
        $this->modelKey = $vehicle->modelKey;
        $this->price = $vehicle->price;
        $this->priceToDisplay = $vehicle->priceToDisplay;

        $this->color = $color;

        $this->extras = $extras;

        $this->pvp = $price->pvp;
        $this->cash = $price->cash;
        $this->discount = $price->discount;

        $this->photoUrl = $photoUrl;
        $this->isNew = false;
        $this->package = $package;
    }

    public function toDto()
    {
        $brand = array("name" => $this->brand);
        $model = new CarModelDTO($this->brand, $this->model, $this->year);
        $vehicle = new VehicleDTO(
            $this->vehicleId,
            $this->makeKey,
            $this->brand,
            $this->modelKey,
            $this->model,
            $this->year,
            $this->fuelType,
            $this->fuelType,
            $this->derivative,
            $this->transmission,
            $this->doors,
            $this->derivative,
            $this->price,
            $this->priceToDisplay
        );

        $color = null;
        if ($this->color != null) {
            if (!$this->color instanceof VehicleOptionDTO) {
                $color = new VehicleOptionDTO($this->color["optionId"], $this->color["optionName"], $this->color["optionTypeName"],
                    $this->color["price"], $this->color["displayPrice"]);
            } else {
                $color = $this->color;
            }
        }

        $extras = array();
        foreach ($this->extras as $extra) {
            if (!$extra instanceof VehicleOptionDTO) {
                array_push($extras, new VehicleOptionDTO($extra["optionId"], $extra["optionName"], $extra["optionTypeName"],
                    $extra["price"], $extra["displayPrice"]));
            } else {
                array_push($extras, $extra);
            }
        }
        $package = null;
        if($this->package) {
            $package = (array)$this->package;
            $package = new VehicleOptionDTO($package["id"], $package["title"], "package", $package["prices"], $package["prices"]."$");
        }
        $price = new Price(floatval($this->pvp), floatval($this->cash), (int)$this->discount);
        return array("id" => $this->id, "brand" => $brand, "model" => $model, "vehicle" => $vehicle, "color" => $color, "package" => $package, "extras" => $extras, "price" => $price, "photoUrl" => $this->photoUrl);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getModel()
    {
        return $this->model;
    }
}
