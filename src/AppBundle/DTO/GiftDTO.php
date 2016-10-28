<?php
/**
 * Created by IntelliJ IDEA.
 * User: apium
 * Date: 10/18/16
 * Time: 5:56 PM
 */

namespace AppBundle\DTO;


class GiftDTO
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $gift_value;
    /**
     * @var string
     */
    public $gift_name;

    public function __construct(int $id, string $gift_value, string $gift_name)
    {
        $this->id = $id;
        $this->gift_value = $gift_value;
        $this->gift_name = $gift_name;
    }
}