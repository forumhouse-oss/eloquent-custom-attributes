<?php

namespace FHTeam\EloquentCustomAttrs\Geo;

use FHTeam\EloquentCustomAttrs\ArrayAttributeWrapper;

/**
 * Wrapper for geographical coordinates
 *
 * @package FHTeam\EloquentCustomAttrs\Geo
 */
class GeoPointWrapper extends ArrayAttributeWrapper
{

    /**
     * Returns point as it is
     *
     * @return array
     */
    public function getPoint()
    {
        return $this->value;
    }

    /**
     * Sets the point as it is
     *
     * @param null|array $point
     */
    public function setPoint($point)
    {
        $this->value = $point;
        $this->refreshConnectedModel();
    }

    /**
     * Returns array of latitude and longitude made from point
     *
     * @return array
     */
    public function getLatLng()
    {
        if (!$this->value) {
            return $this->value;
        }

        return array_reverse($this->value);
    }

    /**
     * Sets point to passed latitude and longitude
     *
     * @param null|array $latLng
     */
    public function setLatLng($latLng)
    {
        if (!$latLng) {
            $this->value = $latLng;
        }

        $this->value = array_reverse($latLng);
        $this->refreshConnectedModel();
    }
}
