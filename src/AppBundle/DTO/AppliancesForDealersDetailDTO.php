<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 30/08/16
 * Time: 11:30
 */

namespace AppBundle\DTO;


use AppBundle\Utils\ApplianceOfferState;
use AppBundle\Utils\Point;

class AppliancesForDealersDetailDTO
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $offerId;

    /**
     * @var string
     */
    public $clientName;

    /**
     * @var string
     */
    public $clientEmail;

    public $appliance;

    public $isNew = "false";

    public $isStock = false;

    public $brand;
    public $model;
    public $longitude;
    public $latitude;

    public $city;
    public $created;
    public $dealerName;
    public $numberOfOffers;

    public function __construct(string $id, string $offerId, string $state, string $created, string $clientName, string $clientEmail, string $city = null, $longitude, $latitude, string $brand, $model, string $derivative, string $transmission, string $motorType, $numberOfDoors, string $price , array $extras, int $numberOfOffers, int $vehicleId, string $dealerId, string $dealerName, $package = null, $color = null)
    {
        $this->id = $id;
        $this->offerId = $offerId;
        if($state == ApplianceOfferState::NEW_OPPORTUNITY) {
          $this->isNew = "new";
        }
        $this->dealerName = $dealerName;
        $this->created = $created;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->city = $city;
        $this->brand = $brand;
        $this->model = $model;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->numberOfOffers = $numberOfOffers;

        $extrasArray = array();
        $extraPrice = 0;
        foreach($extras as $extra) {
            array_push($extrasArray, array(
                "optionName" => preg_replace("/\[.*\]\s*/", "",$extra->name),
                "optionTypeName" => "Option",
                "price" => $extra->price,
                "displayPrice" => $extra->price
            ));
            $extraPrice += $extra->price;
        }
        $totalPrice = floatval($price) + floatval($extraPrice);
        if($package) {
            $package->name = preg_replace("/\[.*\]\s*/", "", $package->name);
            $totalPrice += floatval($package->price);
        }
        $this->appliance = array(
            "vehicle" => array(
                "vehicleId" => $vehicleId,
                "makeKey" => $brand,
                "makeNameToDisplay" => $brand,
                "derivativeToDisplay" => $derivative,
                "package" => $package,
                "transmission" => $transmission,
                "motorType" => $motorType,
                "numberOfDoors" => $numberOfDoors,
                "modelKey" => $model,
                "modelNameToDisplay" => $model,
                "price" => $price,
                "priceToDisplay" => $price
            ),
            "color" => array(
                "optionName" => preg_replace("/\[.*\]\s*/", "",($color != null ? $color->name : "")),
                "optionTypeName" => "Colour",
                "price" => "0",
                "displayPrice" => "0"
            ),
            "extras" => $extrasArray,
            "price" => $totalPrice
        );
    }

    public function getBrand() {
        return $this->brand;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function markInStock()
    {
        $this->isStock = true;
    }
}