<?php

namespace AppBundle;

class Config
{
    public function getRequestsOptions() : array
    {
        return [
            'timeout' => $_ENV['REQUESTS_CURL_TIMEOUT'] ?? 10
        ];
    }
}
