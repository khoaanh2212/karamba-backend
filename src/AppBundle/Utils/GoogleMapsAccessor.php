<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 25/08/16
 * Time: 10:52
 */

namespace AppBundle\Utils;


class GoogleMapsAccessor
{

    const MAPS_API_URL = "http://maps.googleapis.com/maps/api/geocode/json?address=";

    public function getPositionFromZipCode(string $zipCode)
    {
        $response = $this->doCall(self::MAPS_API_URL . $zipCode . "&components=country:ES");
        $geometry = $response->results[0]->geometry->location;
        $city = "";
        foreach ($response->results[0]->address_components as $address_component) {
            if ($address_component->types[0] == "administrative_area_level_2") {
                $city = $address_component->long_name;
            }
        }
        return array(
            "city" => $city,
            "position" => new Point($geometry->lng, $geometry->lat)
        );
    }

    private function doCall(string $path)
    {
        $response = \Requests::get($path);
        return json_decode($response->body);
    }

    public function distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}