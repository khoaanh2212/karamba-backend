<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 7/09/16
 * Time: 10:42
 */

namespace AppBundle\DTO;


class ExtrasDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $price;

    public function __construct(int $id, string $name, float $price = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}