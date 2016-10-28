<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 11/07/16
 * Time: 11:11
 */

namespace AppBundle\DTO;


class DealerApplicationDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $vendorName;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $vendorRole;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $howArrivedHere;

    public function __construct(string $id, string $vendorName, string $dealerName, string $vendorRole, string $phoneNumber, string $email, string $howArrivedHere)
    {
        $this->id = $id;
        $this->vendorName = $vendorName;
        $this->name = $dealerName;
        $this->vendorRole = $vendorRole;
        $this->phone = $phoneNumber;
        $this->email = $email;
        $this->howArrivedHere = $howArrivedHere;
    }
}