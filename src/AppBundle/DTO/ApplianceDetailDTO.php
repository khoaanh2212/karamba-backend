<?php

namespace AppBundle\DTO;


class ApplianceDetailDTO
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
    public $model;

    /**
     * @var string
     */
    public $derivative;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $motorType;

    /**
     * @var string
     */
    public $transmission;

    /**
     * @var int
     */
    public $numberOfDoors;

    /**
     * @var ExtrasDTO
     */
    public $package;

    /**
     * @var ExtrasDTO[]
     */
    public $extras;

    /**
     * @var ExtrasDTO
     */
    public $color;

    /**
     * @var float
     */
    public $totalPrice;

    public function __construct(string $id, string $brand, string $model, string $derivative, float $price, string $motorType, string $transmission, int $numberOfDoors, $package = null, $extras = null, $color = null)
    {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->derivative = $derivative;
        $this->price = $price;
        $this->motorType = $motorType;
        $this->transmission = $transmission;
        $this->numberOfDoors = $numberOfDoors;
        $this->package = $package;
        $this->extras = $extras;
        $this->color = $color;
        $totalPrice = 0.0;
        $totalPrice += $this->price;
        if($this->package) {
            if($this->package["price"]) {
                $totalPrice += $this->package["price"];
            }
        }
        if($this->color) {
            if($this->color["price"]) {
                $totalPrice += $this->color["price"];
            }
        }
        if($this->extras){
            foreach($this->extras as $extra) {
                if($extra["price"]) {
                    $totalPrice += $extra["price"];
                }
            }
        }
        $this->totalPrice = $totalPrice;
    }
}