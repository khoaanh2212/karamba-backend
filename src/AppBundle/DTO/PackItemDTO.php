<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 7/09/16
 * Time: 12:59
 */

namespace AppBundle\DTO;


class PackItemDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string[]
     */
    public $extrasIncluded;

    /**
     * @var float
     */
    public $prices;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $hasReadmore;

    public function __construct(int $id, string $name, array $includeOptionsName, float $price)
    {
        $this->id = $id;
        $this->title = preg_replace("/\[.*\]\s*/", "",$name);
        $this->description = "";
        $this->type = "PVP";
        $this->extrasIncluded = array();
        foreach($includeOptionsName as $option) {
            array_push($this->extrasIncluded, preg_replace("/\[.*\]\s*/", "",$option));
        }
        $this->prices = $price;
        $this->hasReadmore = false;
    }
}