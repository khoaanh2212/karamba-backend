<?php

namespace AppBundle\Utils;

class VehicleOptionsFilter
{

    public function filterByPack($element)
    {
        return count($element->includes) > 1;
    }

    public function filterByExtras($element)
    {
        return count($element->includes) < 1;
    }

    public function filterOptions(array $options)
    {
        $extraOptions = $this->filterExtras($options);
        $packArray = $this->filterPacks($options);
        return array(
            "packs" => $packArray,
            "extras" => $extraOptions
        );
    }

    public function filterExtras(array $options)
    {
        return array_filter($options, array($this, "filterByExtras"));
    }

    public function filterPacks(array $options)
    {
        return array_filter($options, array($this, "filterByPack"));
    }
}