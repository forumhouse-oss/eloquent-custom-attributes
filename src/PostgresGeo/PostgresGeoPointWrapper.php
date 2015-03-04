<?php

namespace FHTeam\EloquentCustomAttrs\PostgresGeo;

use FHTeam\EloquentCustomAttrs\ArrayAttributeWrapper;

/**
 * Wrapper for geographical coordinates as a Postgres Point type
 *
 * @package FHTeam\EloquentCustomAttrs\Geo
 */
class PostgresGeoPointWrapper extends ArrayAttributeWrapper
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
     * @param null|array $value
     */
    public function setPoint($value)
    {
        $this->value = $value;
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
     * @param null|array $value
     */
    public function setLatLng($value)
    {
        if (!$value) {
            $this->value = $value;
        }

        $this->value = array_reverse($value);
        $this->refreshConnectedModel();
    }
}
