<?php

namespace AppBundle\Entity;

use AppBundle\DTO\DealerDTO;
use AppBundle\Utils\Point;
use AppBundle\Utils\SystemClock;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Utils\Cypher;
use AppBundle\Utils\SessionStorageFactory;
use AppBundle\Utils\UUIDGeneratorFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Class DealerApplication
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DealerRepository")
 * @ORM\Table(name="dealers")
 */
class Dealer implements UserInterface, ISerializableDTO
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     */
    private $zipcode;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $vendorName;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $vendorRole;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $schedule;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryConditions;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $specialConditions;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     */
    private $phoneNumber;

    /**
     * @var DealerCondition[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DealerCondition")
     * @ORM\JoinTable(name="dealer_conditions_assoc",
     *     joinColumns={@ORM\JoinColumn(name="dealer_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")})
     */
    private $generalConditions;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var Point
     * @ORM\Column(type="point")
     */
    private $position;

    public function getGeneralConditions()
    {
        return $this->generalConditions;
    }

    public function clearGeneralConditions()
    {
        $this->generalConditions = new ArrayCollection();
    }

    public function getVendorName()
    {
        return $this->vendorName;
    }

    public function __construct(
        string $name,
        string $phoneNumber,
        string $vendorName,
        string $vendorRole,
        string $email,
        string $password,
        string $schedule = null,
        string $deliveryConditions = null,
        string $specialConditions = null,
        string $address = null,
        string $description = null,
        string $zipcode = null,
        Point $position = null
    ) {
        $this->id                 = UUIDGeneratorFactory::getInstance()->generateId();
        $this->name               = $name;
        $this->description        = $description;
        $this->address            = $address;
        $this->phoneNumber        = $phoneNumber;
        $this->vendorName         = $vendorName;
        $this->vendorRole         = $vendorRole;
        $this->email              = $email;
        $this->password           = Cypher::getInstance()->encrypt($password);
        $this->schedule           = $schedule;
        $this->deliveryConditions = $deliveryConditions;
        $this->specialConditions  = $specialConditions;
        $this->created            = SystemClock::now()->getDate();
        $this->updated            = SystemClock::now()->getDate();
        $this->generalConditions  = new ArrayCollection();
        $this->zipcode            = $zipcode;
        $this->position           = $position;
    }

    public function setZipCode(string $zipCode)
    {
        $this->zipcode = $zipCode;
    }

    public function setPosition(Point $position)
    {
        $this->position = $position;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setUpdated()
    {
        $this->updated = SystemClock::now()->getDate();
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setVendorName(string $vendorName)
    {
        $this->vendorName = $vendorName;
    }

    public function setVendorRole(string $vendorRole)
    {
        $this->vendorRole = $vendorRole;
    }

    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setPassword(string $password)
    {
        $this->password = Cypher::getInstance()->encrypt($password);
    }

    public function setSchedule(string $schedule)
    {
        $this->schedule = $schedule;
    }

    public function setDeliveryConditions(string $deliveryConditions)
    {
        $this->deliveryConditions = $deliveryConditions;
    }

    public function setSpecialConditions(string $specialConditions)
    {
        $this->specialConditions = $specialConditions;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    public function resetGeneralConditions()
    {
        $this->generalConditions = array();
    }

    public function addGeneralCondition(DealerCondition $condition)
    {
        $this->generalConditions[] = $condition;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_DEALER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function validate(string $username, string $password): bool
    {
        if ($username != $this->email || !Cypher::getInstance()->verify($password, $this->password)) {
            throw new UsernameNotFoundException();
        }
        $sessionStorage = SessionStorageFactory::getInstance();
        $sessionStorage->setValue("TOKEN_ID", UUIDGeneratorFactory::getInstance()->generateId());
        $sessionStorage->setValue("USERNAME", $this->email);
        return true;
    }

    public function toDTO()
    {
        return new DealerDTO($this->name, $this->vendorName, $this->vendorRole, $this->email, $this->description,
            $this->address, $this->zipcode, $this->schedule, $this->deliveryConditions, $this->specialConditions,
            $this->generalConditions, $this->updated, $this->created);
    }
}