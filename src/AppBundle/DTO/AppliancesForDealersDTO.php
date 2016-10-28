<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 30/08/16
 * Time: 11:30
 */

namespace AppBundle\DTO;


use AppBundle\Utils\Point;

class AppliancesForDealersDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $clientName;

    /**
     * @var string
     */
    public $clientEmail;

    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $model;

    public $extras;

    /**
     * @var int
     */
    public $numberOffers;

    /**
     * @var int
     */
    public $vehicleId;

    /**
     * @var string
     */
    public $dealerId;

    /**
     * @var string
     */
    public $pvp;

    public $package;

    public $color;

    public $state;

    public $isRead = false;

    public $created;

    public function __construct(string $id, string $clientName, string $clientEmail, string $brand, string $model, string $price , array $extras, int $numberOfOffers, int $vehicleId, string $dealerId, bool $isRead = false, string $state, $created, $package = null, $color = null)
    {
        $this->id = $id;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->brand = $brand;
        $this->model = $model;
        $this->extras = $extras;
        $this->numberOffers = $numberOfOffers;
        $this->vehicleId = $vehicleId;
        $this->dealerId = $dealerId;
        $this->package = $package;
        $this->color = $color;
        $this->pvp = $price;
        $this->isRead = $isRead;
        $this->state = $state;
        $this->created = $created;
    }
}