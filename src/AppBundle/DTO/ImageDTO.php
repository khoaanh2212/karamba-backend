<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/07/16
 * Time: 12:08
 */

namespace AppBundle\DTO;


class ImageDTO
{
    public $url;

    public $label;

    public function __construct(string $url, string $label = null)
    {
        $this->label = $label;
        $this->url = $url;
    }
}