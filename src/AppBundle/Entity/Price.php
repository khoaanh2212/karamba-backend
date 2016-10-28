<?php

namespace AppBundle\Entity;


class Price
{

    public $pvp;
    public $cash;
    public $discount;

    public function __construct($pvp, $cash, $discount)
    {
        $this->pvp = $pvp;
        $this->cash = $cash;
        $this->discount = $discount;
    }

}
