<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 5/08/16
 * Time: 16:31
 */

namespace AppBundle\DTO;

class VehicleOptionDTO{

    /**
    * @var int
    */
    public $optionId;
    /**
     * @var string
     */
    public $optionName;
    /**
     * @var string
     */
    public $optionTypeName;
    /**
     * @var float
     */
    public $price;
    /**
     * @var string
     */
    public $displayPrice;

    /**
     * @var string
     */
    public $disabled;

    /**
     * @var int
     */
    public $requiredBy;

    /**
     * @var int
     */
    public $includedBy;

    /**
     * @var array
     */
    public $requires;

    /**
     * VehicleOptionDTO constructor.
     * @param int $optionId
     * @param string $optionName
     * @param string $optionTypeName
     * @param float $price
     * @param string $displayPrice
     * @param null $requires
     */
    public function __construct(int $optionId, string $optionName, string $optionTypeName, float $price, string $displayPrice, $requires = null)
    {
        $this->optionId = $optionId;
        $this->optionName = $optionName;
        $this->optionTypeName = $optionTypeName;
        $this->price = $price;
        $this->displayPrice = $displayPrice;
        $this->disabled = "";
        if(!$requires) $requires = array();
        $this->requires = $requires;
    }

    public function setRequiredBy(int $packageId)
    {
        $this->requiredBy = $packageId;
        $this->disabled = "disabled";
    }

    public function setIncludedBy (int $packageId)
    {
        $this->includedBy = $packageId;
        $this->price = 0;
        $this->disabled = true;
    }
}