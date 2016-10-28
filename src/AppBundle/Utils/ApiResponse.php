<?php

namespace AppBundle\Utils;


class ApiResponse
{
    public $status;
    public $data;

    public function __construct (int $errorCode, $data = null)
    {
        $this->status = $errorCode;
        $this->data = $data;
    }
}