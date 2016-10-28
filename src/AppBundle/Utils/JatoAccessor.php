<?php


namespace AppBundle\Utils;


use AppBundle\Config;
use AppBundle\DTO\JatoCredentialsDTO;

class JatoAccessor
{
    const LOGIN_PATH = "/authentication/login";
    const LANGUAGES_PATH = "/languages/set";
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $market;

    /**
     * @var array
     */
    private $requestOptions;

    /**
     * @var VehicleOptionsFilter
     */
    private $optionsFilter;

    public function __construct(string $host, string $username, string $password, string $market, Config $config, VehicleOptionsFilter $optionsFilter)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->market = $market;
        $this->requestOptions = $config->getRequestsOptions();
        $this->optionsFilter = $optionsFilter;
    }

    public function login()
    {
        $data = array(
            "email" => $this->username,
            "password" => $this->password
        );
        $headers = array(
            "Content-Type" => "application/json;charset=UTF-8"
        );
        $request = \Requests::POST($this->host.self::LOGIN_PATH, $headers, json_encode($data), $this->requestOptions);
        $response = json_decode($request->body);
        $this->setLanguage($response->token);
        return new LoggedJatoClient($this->host, $this->market, $response->token, $response->username, $this->requestOptions, $this->optionsFilter);
    }

    private function setLanguage(string $token)
    {
        $data = "2";
        $headers = array(
            "Content-Type" => "application/json;charset=UTF-8",
            "Authorization" => "Basic ".$token
        );
        \Requests::POST($this->host.self::LANGUAGES_PATH, $headers, $data, $this->requestOptions);
    }
}
