<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 15:37
 */

namespace AppBundle\Entity;

use AppBundle\DTO\ClientDTO;
use AppBundle\Utils\Point;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Utils\Cypher;
use AppBundle\Utils\SessionStorageFactory;
use AppBundle\Utils\UUIDGeneratorFactory;


/**
 * Class Client
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 * @ORM\Table(name="clients")
 */
class Client implements UserInterface
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
     * @ORM\Column(type="string", length=100)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $zipCode;

    /**
     * @var Point
     * @ORM\Column(type="point")
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    public function getPosition(): Point
    {
        return $this->position;
    }
    

    public function __construct(string $name, string $email, string $zipCode, string $city, string $password, Point $position)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->name = $name;
        $this->email = $email;
        $this->zipCode = $zipCode;
        $this->password = Cypher::getInstance()->encrypt($password);
        $this->position = $position;
        $this->city = $city;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = Cypher::getInstance()->encrypt($password);
    }

    public function setZipcode(string $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function setPosition(Point $position)
    {
        $this->position = $position;
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
        return array('ROLE_CLIENT');
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
        if($username != $this->email || !Cypher::getInstance()->verify($password, $this->password)) {
            throw new UsernameNotFoundException();
        }
        $sessionStorage = SessionStorageFactory::getInstance();
        $sessionStorage->setValue("TOKEN_ID", UUIDGeneratorFactory::getInstance()->generateId());
        $sessionStorage->setValue("USERNAME", $this->email);
        return true;
    }

    public function toDTO() {
        return new ClientDTO($this->name, $this->email, $this->zipCode);
    }
}
