<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 7/09/16
 * Time: 10:07
 */

namespace AppBundle\DTO;


class PackDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int[]
     */
    public $included;

    /**
     * @var int[]
     */
    public $required;

    /**
     * @var int[]
     */
    public $excluded;

    public function __construct(int $id, array $included, array $required, array $excluded)
    {
        $this->id = $id;
        $this->included = $included;
        $this->required = $required;
        $this->excluded = $excluded;
    }
}