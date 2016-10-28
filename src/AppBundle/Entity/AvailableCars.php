<?php

namespace AppBundle\Entity;


use AppBundle\DTO\CarModelDTO;
use AppBundle\Utils\UUIDGeneratorFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AvailableCars
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvailableCarsRepository")
 * @ORM\Table(name="availablecars")
 */
class AvailableCars
{

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
     * @ORM\Id
     */
    private $brand;


    /**
     * @var string[]
     * @ORM\Column(type="json_array")
     */
    private $models;

    public function __construct(string $dealerId, string $brand, array $models)
    {
        $this->dealerId = $dealerId;
        $this->brand = $brand;
        $this->models = $models;
    }

    public function setModels(array $models)
    {
        $this->models = $models;
    }

    public function modelsToDTO()
    {
        $modelsDTO = array();
        foreach($this->models as $model){
            array_push($modelsDTO, new CarModelDTO($model['brand'], $model['name'], $model['year']));
        }
        return $modelsDTO;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }
}
