<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 21/10/16
 * Time: 13:29
 */

namespace AppBundle\DTO;


class ColorDTO
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

    /**
     * @var int[]
     */
    public $dependencies;

    /**
     * @var int[]
     */
    public $excludes;

    public function __construct(int $id, string $name, float $price, array $dependencies, array $excludes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->dependencies = $dependencies;
        $this->excludes = $excludes;
    }
}