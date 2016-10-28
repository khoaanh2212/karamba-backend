<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 14/09/16
 * Time: 23:39
 */

namespace AppBundle\DTO;


class CarApplianceDTO
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $photo;

    /**
     * @var string
     */
    public $model;
    /**
     * @var string
     */
    public $packageName;
    /**
     * @var string[]
     */
    public $extrasName;
    /**
     * @var string
     */
    public $color;
    /**
     * @var int
     */
    public $numberOfOffers;

    /**
     * CarApplianceDTO constructor.
     * @param string $id
     * @param string $brand
     * @param string $model
     * @param ExtrasDTO[] $extras
     * @param int $numberOfOffers
     * @param ExtrasDTO|null $package
     * @param ExtrasDTO|null $color
     */
    public function __construct(string $id, string $brand, string $model, string $photo, array $extras = null, int $numberOfOffers = 0, $package = null, $color = null)
    {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->extrasName = array();
        if(!$extras) $extras = array();
        foreach($extras as $extraItem)
        {
            array_push($this->extrasName, preg_replace("/\[.*\]\s*/", "",$extraItem["name"]));
        }
        $this->numberOfOffers = $numberOfOffers;
        $this->photo = $photo;
        if($package) {
            $this->packageName = preg_replace("/\[.*\]\s*/", "",$package["name"]);
        }
        if($color) {
            $this->color = preg_replace("/\[.*\]\s*/", "", $color["name"]);
        }
    }
}