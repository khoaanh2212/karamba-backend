<?php

namespace AppBundle\DTO;
use AppBundle\Utils\Point;

class DealerDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $zipCode;

    /**
     * @var string
     */
    public $vendorName;

    /**
     * @var string
     */
    public $vendorRole;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $schedule;

    /**
     * @var string
     */
    public $deliveryConditions;

    /**
     * @var string
     */
    public $specialConditions;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var boolean
     */
    public $firstUse;

    /**
     * @var string
     */
    public $description;
    
    /**
     * @var DealerConditionDTO[]
     */
    public $generalConditions;

    /**
     * @var ImageDTO
     */
    public $avatar;

    public $background;

    public $longitude;
    public $latitude;

    public function __construct(string $name, string $vendorName, string $vendorRole, string $email, string $description = null, string $address = null, string $zipCode = null, string $schedule = null, string $deliveryConditions = null, string $specialConditions = null, $generalConditions = null, $updated = null, $created = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->vendorName = $vendorName;
        $this->vendorRole = $vendorRole;
        $this->email = $email;
        $this->schedule = $schedule;
        $this->deliveryConditions = $deliveryConditions;
        $this->specialConditions = $specialConditions;
        $this->firstUse = ($updated == $created) ? true : false;
        $conditions = array();
        foreach($generalConditions as $condition) {
            array_push($conditions, $condition->toDTO());
        }
        $this->generalConditions = $conditions;
    }

    public function setAvatar(ImageDTO $avatar)
    {
        $this->avatar = $avatar;
    }

    public function setBackground(ImageDTO $background)
    {
        $this->background = $background;
    }

    public function setPosition(Point $position) {
        $this->longitude = $position->getLongitude();
        $this->latitude = $position->getLatitude();
    }
}