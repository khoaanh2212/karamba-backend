<?php

namespace AppBundle\Factory;

use AppBundle\DTO\ExtraResponseDTO;
use AppBundle\DTO\VehicleCategoryDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\DTO\VehiclePerformanceDTO;
use AppBundle\DTO\VehicleSummaryDTO;
use AppBundle\Utils\VehicleOptionsFilter;

class VehicleFactory
{

    public static $optionsPrize;
    public static $added;
    public static function vehicleDTOfromJSON($json)
    {
        $vehicle = new VehicleDTO(
            $json->vehicleId,
            $json->makeKey,
            $json->makeNameToDisplay,
            $json->modelKey,
            $json->modelNameToDisplay,
            $json->modelYearToDisplay,
            $json->fuelType,
            $json->fuelTypeToDisplay,
            $json->derivative,
            $json->transmission,
            $json->numberOfDoorsToDisplay,
            $json->derivativeToDisplay,
            $json->price,
            $json->priceToDisplay
        );
        return $vehicle;
    }

    public static function vehicleOptionDTOfromJSON($json)
    {
        $option = new VehicleOptionDTO(
            $json->optionId,
            $json->optionName,
            $json->optionTypeName,
            $json->price,
            $json->displayPrice);
        return $option;
    }

    public static function performanceDTOfromJSON($vehicleId, $json)
    {
        $performance = new VehiclePerformanceDTO(
            $vehicleId,
            $json->maximumPowerInHpPs15304Value,
            $json->co2LevelgKm7603Value,
            $json->fuelConsumptionKm42005Value
        );
        return $performance;
    }

    public static function summaryDTOfromJSON($json)
    {
        $vehicles = array();
        foreach ($json as $v) {
            array_push($vehicles, new VehicleDTO(
                $v->vehicleId,
                $v->makeKey,
                $v->makeNameToDisplay,
                $v->modelKey,
                $v->modelNameToDisplay,
                $v->modelYearToDisplay,
                $v->fuelType,
                $v->fuelTypeToDisplay,
                $v->derivative,
                $v->transmission,
                $v->numberOfDoorsToDisplay,
                $v->derivativeToDisplay,
                $v->price,
                $v->priceToDisplay
            ));
        }
        return self::summaryDTOfromVehiclesDTO($vehicles);
    }

    public static function summaryDTOfromVehiclesDTO(array $vehicles)
    {
        $boxes = array();
        $doors = array();
        $fuels = array();
        foreach ($vehicles as $v) {
            if (!in_array($v->transmission, $boxes)) array_push($boxes, $v->transmission);
            if (!in_array($v->numberOfDoorsToDisplay, $doors)) array_push($doors, $v->numberOfDoorsToDisplay);
            if (!in_array($v->fuelTypeToDisplay, $fuels)) array_push($fuels, $v->fuelTypeToDisplay);
        }
        sort($doors);
        return new VehicleSummaryDTO($vehicles, $fuels, $boxes, $doors);
    }

    private static function getPrizeFromOption(int $index)
    {
        $prize = 0;
        if(!array_key_exists($index, self::$optionsPrize)) {
            return $prize;
        }
        array_push(self::$added, $index);
        $optionsPrizeByIndex = self::$optionsPrize[$index];
        $prize += $optionsPrizeByIndex["price"];
        if ($optionsPrizeByIndex["requires"]) {
            foreach($optionsPrizeByIndex["requires"] as $require)
            {
                if(!in_array($require, self::$added)) {
                    $prize += self::getPrizeFromOption($require);
                }
            }
        }
        return $prize;
    }

    public static function categoriesDTOfromJSON($json, $packageId = null)
    {
        $vehicleFilter = new VehicleOptionsFilter();
        $optionsDTO    = $vehicleFilter->filterOptions($json);
        $jsonOptions   = $optionsDTO["extras"];
        $options       = array();
        $packageJson   = null;
        if ($packageId != null) {
            $packs = $optionsDTO["packs"];
            foreach ($packs as $pack) {
                if ($pack->optionId == $packageId) {
                    $packageJson = $pack;
                }
            }
        }
        $return = new ExtraResponseDTO();

        self::$optionsPrize = array();

        foreach ($jsonOptions as $option) {
            if(strpos(strtolower($option->optionName), 'pintura') !== false) {
                self::$optionsPrize[$option->optionId] = array(
                    "price" => $option->price,
                    "requires" => array(),
                    "excludes" => array()
                );
                foreach($option->requires as $require) {
                    foreach($require->optionId as $optionId) {
                        if(array_key_exists($option->optionId, self::$optionsPrize)) {
                            array_push(self::$optionsPrize[$option->optionId]["requires"], $optionId);
                        }

                    }
                }
                foreach($option->excludes as $require) {
                    if(array_key_exists($option->optionId, self::$optionsPrize)) {
                        array_push(self::$optionsPrize[$option->optionId]["excludes"], $require);
                    }
                }
            }
        }
        foreach ($jsonOptions as $option) {
            $option   = self::validOption($option);
            $optionId = $option->optionId;
            if ($option->optionType == "C" && $option->categoryName == "Color exterior") {
                self::$added = array();
                self::$optionsPrize[$option->optionId] = array(
                    "price" => $option->optionId,
                    "requires" => $option->requires ? $option->requires[0]->optionId : null
                );
                $price = self::getPrizeFromOption($option->optionId);
                $return->addColor(new VehicleOptionDTO($optionId, $option->optionName, $option->optionTypeName,
                    $price, $price."$", self::$added));
            } else {
                if(array_key_exists($option->optionId, self::$optionsPrize)) {
                    continue;
                }
                if (!isset($options[$option->categoryName])) $options[$option->categoryName] = array();
                $extra = new VehicleOptionDTO(
                    $option->optionId,
                    $option->optionName,
                    $option->optionTypeName,
                    $option->price,
                    $option->displayPrice);
                if ($packageJson) {
                    if(in_array($option, $packageJson->excludes)) {
                        continue;
                    }
                    if (in_array($optionId, $packageJson->includes)) {
                        $extra->setIncludedBy($packageId);
                    }
                    if (in_array($option, $packageJson->requires)) {
                        $extra->setRequiredBy($packageId);
                    }
                }
                array_push($options[$option->categoryName],
                    $extra);
            }
        }
        $result = array();
        foreach ($options as $key => $values) {
            array_push($result, new VehicleCategoryDTO($key, $values));
        }
        $return->setExtras($result);
        return $return;
    }

    private static function validOption($option)
    {
        $name                 = explode("]", $option->optionName);
        $option->optionName   = count($name) == 2 ? trim($name[1]) : $option->optionName;
        $option->price        = $option->price ? $option->price : 0;
        $option->categoryName = $option->categoryName ? $option->categoryName : 'undefined';
        return $option;
    }
}
