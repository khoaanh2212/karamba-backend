<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 8/07/16
 * Time: 15:47
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Utils\SystemClock;
use AppBundle\Utils\UUIDGeneratorFactory;

/**
 * Class PendingDealerApplication
 * @package AppBundle\Entity
 */
interface AcceptedDealerApplication extends ISerializableDTO
{
    public function refreshToken();

    public function checkValidToken(): bool ;

    public function process(): ProcessedDealerApplication;
    
    public function getMail();
    public function getName();
    public function getVendorName();
    public function getVendorRole();
    public function getPhoneNumber();
    public function getToken();
    public function toTokenDTO();
}