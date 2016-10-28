<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 18:31
 */

namespace AppBundle\Utils;


class Point
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    public function __construct(float $longitude = null, float $latitude = null)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getSquareCoordinates(int $distanceInKm)
    {
        $distanceInMiles = $distanceInKm * 0.621371;
        return array(
            new Point($this->longitude-$this->getLongitudeSquareCoordinate($distanceInMiles, $this->latitude), $this->latitude - ($distanceInMiles / 69)),
            new Point($this->longitude+$this->getLongitudeSquareCoordinate($distanceInMiles, $this->latitude), $this->latitude + ($distanceInMiles / 69))
        );
    }

    private function getLongitudeSquareCoordinate(float $distanceInMiles, float $latitude): float
    {
        return $distanceInMiles/abs(cos(deg2rad($latitude)) * 69);
    }


}