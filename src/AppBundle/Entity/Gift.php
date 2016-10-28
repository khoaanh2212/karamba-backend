<?php
/**
 * Created by IntelliJ IDEA.
 * User: apium
 * Date: 10/18/16
 * Time: 5:54 PM
 */

namespace AppBundle\Entity;

use AppBundle\DTO\GiftDTO;
use AppBundle\Utils\UUIDGeneratorFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Gift
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GiftRepository")
 * @ORM\Table(name="gifts")
 */
class Gift implements ISerializableDTO
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
     * @ORM\Column(type="string", length=16)
     */
    private $gift_value;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $gift_name;

    public function __construct(string $gift_value, string $gift_name)
    {
        $this->gift_value = $gift_value;
        $this->gift_name = $gift_name;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setGiftValue(string $gift_value)
    {
        $this->gift_value = $gift_value;
    }

    public function setGiftName(string $gift_name)
    {
        $this->gift_name = $gift_name;
    }

    public function getGiftName()
    {
        return $this->gift_name;
    }

    public function toDTO()
    {
        return new GiftDTO($this->id, $this->gift_value, $this->gift_name);
    }
}