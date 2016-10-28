<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 15/07/16
 * Time: 16:41
 */

namespace AppBundle\DTO;


class DealerConditionDTO
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $text;

    public function __construct(int $id, string  $text)
    {
        $this->id = $id;
        $this->text = $text;
    }
}