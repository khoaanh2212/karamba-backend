<?php

namespace AppBundle\Entity;
use AppBundle\DTO\TokenDTO;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\DTO\DealerApplicationDTO;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGeneratorFactory;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;;

/**
 * Class DealerApplication
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DealerApplicationRepository")
 * @UniqueEntity({"email"})
 * @ORM\Table(name="dealerapplications")
 */
class DealerApplication implements PendingDealerApplication, AcceptedDealerApplication, ProcessedDealerApplication, RejectedDealerApplication
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
    private $discr;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $vendorName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $dealerName;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $vendorRole;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $howArrivedHere;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     */
    private $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $expiration;

    const PENDING = "pending";
    const ACCEPTED = "accepted";
    const PROCESSED = "processed";
    const REJECTED = "rejected";

    protected function __construct(string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere, string $discr)
    {
        $this->id = UUIDGeneratorFactory::getInstance()->generateId();
        $this->vendorName = $vendorName;
        $this->dealerName = $dealerName;
        $this->vendorRole = $vendorRole;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->howArrivedHere = $howArrivedHere;
        $this->discr = $discr;
        $this->expiration = SystemClock::now()->getDate();
    }

    public function getMail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->dealerName;
    }

    public function getVendorName()
    {
        return $this->vendorName;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function constructPendingApplication(string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere): PendingDealerApplication
    {
        return new DealerApplication($vendorName, $dealerName, $vendorRole, $phoneNumber, $email, $howArrivedHere, self::PENDING);
    }

    public static function constructAcceptedApplication(string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere): AcceptedDealerApplication
    {
        $dealerApplication = new DealerApplication($vendorName, $dealerName, $vendorRole, $phoneNumber, $email, $howArrivedHere, self::ACCEPTED);
        $dealerApplication->refreshToken();
        return $dealerApplication;
    }
    public static function constructProcessedApplication(string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere): ProcessedDealerApplication
    {
        $dealerApplication = new DealerApplication($vendorName, $dealerName, $vendorRole, $phoneNumber, $email, $howArrivedHere, self::PROCESSED);
        return $dealerApplication;
    }
    public static function constructRejectedApplication(string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere): RejectedDealerApplication
    {
        $dealerApplication = new DealerApplication($vendorName, $dealerName, $vendorRole, $phoneNumber, $email, $howArrivedHere, self::REJECTED);
        return $dealerApplication;
    }
    public function accept() : AcceptedDealerApplication
    {
        $this->refreshToken();
        $this->discr = self::ACCEPTED;
        return $this;
    }
    
    public function process() : ProcessedDealerApplication
    {
        $this->discr = self::PROCESSED;
        $this->token = null;
        return $this;
    }

    public function reject() : RejectedDealerApplication
    {
        $this->discr = self::REJECTED;
        return $this;
    }
    
    public function toDTO()
    {
        return new DealerApplicationDTO($this->id, $this->vendorName, $this->dealerName, $this->vendorRole, $this->phoneNumber, $this->email, $this->howArrivedHere);
    }

    public function toTokenDTO(){
        return new TokenDTO($this->token, $this->email, $this->expiration);
    }

    public function refreshToken()
    {
        $this->token = UUIDGeneratorFactory::getInstance()->generateId();
        $this->expiration = SystemClock::now()->getDealerApplicationExpirationDate();
    }

    public function __toString()
    {
        $expirationString =($this->expiration) ? $this->expiration->format('Y-m-d H:i:s'):null;
        return get_class($this).">>[id]:".$this->id.",[vendorName]:".$this->vendorName.",[dealerName]:".$this->dealerName.",[vendorRole]:".$this->vendorRole.",[phoneNumber]:".$this->phoneNumber.",[email]:".$this->email.",[howArrived]:".$this->howArrivedHere.",[token]:".$this->token.",[expiration]:". $expirationString;
    }
    
    public function _getDiscr(){
        return $this->discr;
    }
    
    public function setVendorName($vendorName)
    {
        $this->vendorName = $vendorName;
    }

    public function checkValidToken(): bool
    {
        if(!SystemClock::now()->isExpirationDateWithinTime($this->expiration)) {
            throw new NonceExpiredException("Token has expired");
        }
        return true;
    }

    public function getVendorRole()
    {
        return $this->vendorRole;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
}