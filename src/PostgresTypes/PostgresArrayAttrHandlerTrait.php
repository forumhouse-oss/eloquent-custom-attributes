<?php namespace FHTeam\EloquentCustomAttrs\PostgresTypes;

use Exception;
use FHTeam\EloquentCustomAttrs\ArrayAttributeWrapper;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class PostgresArrayAttrHandlerTrait
 *
 * @package PostgresTypes
 */
trait PostgresArrayAttrHandlerTrait
{
    /**
     * @var array
     */
    protected $postgresArrayAttrWrappers = [];

    /**
     * @param string $key
     *
     * @return array
     */
    public function handleGetAttributePostgresArray($key)
    {
        if (isset($this->postgresArrayAttrWrappers[$key])) {
            return $this->postgresArrayAttrWrappers[$key];
        }

        $wrapper = new ArrayAttributeWrapper(
            $this,
            $key,
            $this->postgresArrayToPhpArray(parent::getAttribute($key))
        );

        $this->postgresArrayAttrWrappers[$key] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string $key
     * @param array  $value
     *
     * @throws \Exception
     */
    public function handleSetAttributePostgresArray($key, $value)
    {
        parent::setAttribute($key, $this->phpArrayToPostgresArray($value));
    }

    /**
     * Unpacks geometric POINT type data representation for ex. '(1.2,3.4)'
     *
     * @param string $data Data to unpack as a point
     *
     * @return array [X, Y]
     */
    public function postgresArrayToPhpArray($data)
    {
        $data = trim($data, "{} \t\n\r\0\x0B");
        if ('' === $data) {
            return [];
        }

        return explode(',', $data);
    }

    /**
     * Packs geometric POINT type data representation for ex. '(1.2,3.4)'
     *
     * @param array|Arrayable $data Array of coordinates for the point - [X, Y]
     *
     * @return string
     * @throws Exception
     */
    public function phpArrayToPostgresArray($data)
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            throw new Exception("Passed value cannot be converted to array: ".serialize($data));
        }

        return '{'.implode(',', $data).'}';
    }
}
