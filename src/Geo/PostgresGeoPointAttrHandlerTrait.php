<?php

namespace FHTeam\EloquentCustomAttrs\Geo;

use Eloquent;
use Exception;
use FHFW\Database\Helper\TypeConversionHelper;

/**
 * Class GeoAttrHandlerTrait
 *
 * @mixin Eloquent
 * @package FHTeam\EloquentCustomAttrs
 */
trait PostgresGeoPointAttrHandlerTrait
{
    /**
     * @var array
     */
    protected $postgresGeoPointAttrWrappers = [];

    /**
     * @param string $key
     *
     * @return array
     */
    public function handleGetAttributePostgresGeoPoint($key)
    {
        if (isset($this->postgresGeoPointAttrWrappers[$key])) {
            return $this->postgresGeoPointAttrWrappers[$key];
        }

        $wrapper = new GeoPointWrapper(
            $this,
            $key,
            $this->postgresPointToArray(parent::getAttribute($key))
        );

        $this->postgresGeoPointAttrWrappers[$key] = $wrapper;
        return $wrapper;
    }

    /**
     * @param string $key
     * @param array  $value
     *
     * @throws \Exception
     */
    public function handleSetAttributePostgresGeoPoint($key, $value)
    {
        parent::setAttribute($key, $this->arrayToPostgresPoint($value));
    }

    /**
     * Unpacks geometric POINT type data representation for ex. '(1.2,3.4)'
     *
     * @param string $data Data to unpack as a point
     *
     * @return array [X, Y]
     */
    public function postgresPointToArray($data)
    {
        $data = trim($data, "() \t\n\r\0\x0B");
        if ('' === $data) {
            return null;
        }

        return explode(',', $data);
    }

    /**
     * Packs geometric POINT type data representation for ex. '(1.2,3.4)'
     *
     * @param array $data Array of coordinates for the point - [X, Y]
     *
     * @return string
     * @throws Exception
     */
    public function arrayToPostgresPoint(array $data)
    {
        if (count($data) !== 2) {
            throw new Exception("Point should have only two coordinates");
        }

        return '(' . implode(',', $data) . ')';
    }
}
