<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\DTO\DealerConditionDTO;

/**
 * Class DealerCondition
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DealerConditionRepository")
 * @ORM\Table(name="dealerconditions")
 */
class DealerCondition implements ISerializableDTO
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $conditionName;

    public function __construct(string $conditionName)
    {
        $this->conditionName = $conditionName;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setConditionName(string $conditionName)
    {
        $this->conditionName = $conditionName;
    }

    public function toDTO()
    {
        return new DealerConditionDTO($this->id, $this->conditionName);
    }
}