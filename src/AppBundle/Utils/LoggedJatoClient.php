<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 28/07/16
 * Time: 19:01
 */

namespace AppBundle\Utils;


class LoggedJatoClient
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $market;

    /**
     * @var array
     */
    private $options;

    private $optionsFilter;

    public function __construct(string $host, string $market, string $token, string $user, array $options, VehicleOptionsFilter $optionsFilter)
    {
        $this->host = $host;
        $this->market = $market;
        $this->token = $token;
        $this->username = $user;
        $this->options = $options;
        $this->optionsFilter = $optionsFilter;
    }

    public function getBrands()
    {
        return $this->doPostCall("/makes/v2/load", array(array(
            "databaseName" => $this->market
        )));
    }

    public function getUserName() : string
    {
        return $this->username;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getBrandsModels(array $brands)
    {
        $neededBrands = array();
        foreach($brands as $brand){
            array_push($neededBrands, array("name" => $brand));
        }
        return $this->doPostCall("/models/v2/load", array(
            array(
                "databaseName" => $this->market,
                "selections" => $neededBrands
            )
        ));
    }

    public function getVehicles(string $brand)
    {
        return $this->doPostCall("/vehicles/v2/load", array(
            array(
                "databaseName" => $this->market,
                "selections" => array(
                    array(
                        "makeName" => $brand
                    )
                )
            )
        ));
    }
    public function getVehicle(string $brand, string $model)
    {
        return $this->doPostCall("/vehicles/v2/load", array(
            array(
                "databaseName" => $this->market,
                "selections" => array(
                    array(
                        "makeName" => $brand,
                        "modelName" => $model
                    )
                )
            )
        ));
    }

    public function filterByPack($element)
    {
        return count($element->includes) > 1;
    }

    public function filterByExtras($element)
    {
        return count($element->includes) < 1;
    }

    public function getVehiclePacksAndExtras(string $id)
    {
        $options = $this->getOptions($id);
        return array(
            "photo" => $options["photo"],
            "price" => $options["price"],
            "options" => $this->optionsFilter->filterOptions($options["options"])
        );
    }

    private function doPostCall(string $path, array $body)
    {
        $headers = array(
            "Content-Type" => "application/json;charset=UTF-8",
            "Authorization" => "Basic ".$this->token

        );
        $response = \Requests::post($this->host.$path, $headers, json_encode($body), $this->options);
        return json_decode($response->body)[0];
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getVehicleDetail(string $id)
    {
        return $this->doPostCall("/vehiclesbuilder/build", array(
            'benchmarkVehicleId' => $id,
            'markets' => array(array('databaseName' => $this->market, 'vehicles' => array(array('vehicleId' => $id))))));
    }

    /**
     * @param string $id
     * @return mixed
     */
    private function getOptions(string $id)
    {
        $vehicleDetail = $this->getVehicleDetail($id);
        $photo = "";
        if(count($vehicleDetail->vehiclePhotos) > 0) {
            $photo = $vehicleDetail->vehiclePhotos[0];
        }
        $price = $vehicleDetail->vehicleHeaderInfo->price;
        $options = $vehicleDetail->vehicleOptionPage->optionInfos;
        $vehiclePhoto = $photo;
        $optionsData = array(
            "price" => $price,
            "photo" => $vehiclePhoto,
            "options" => $options
        );
        return $optionsData;
    }
}
