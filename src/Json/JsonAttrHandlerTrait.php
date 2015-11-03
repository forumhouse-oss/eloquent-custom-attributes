<?php namespace FHTeam\EloquentCustomAttrs\Json;

use Eloquent;
use FHTeam\EloquentCustomAttrs\ArrayAttributeWrapper;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class for working with a JSON string attribute of the model as with array
 *
 * @mixin Eloquent
 * @package FHTeam\EloquentCustomAttrs
 */
trait JsonAttrHandlerTrait
{
    protected $jsonAttrWrappers = [];

    /**
     * @param string $key
     *
     * @return array
     */
    public function handleGetAttributeJson($key)
    {
        if (isset($this->jsonAttrWrappers[$key])) {
            return $this->jsonAttrWrappers[$key];
        }

        $wrapper = $this->setWrapperForKey($key);

        return $wrapper;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function handleSetAttributeJson($key, $value)
    {
        if ($value instanceof JsonSerializable) {
            $value = $value->jsonSerialize();
        }

        if (!is_string($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $this->setWrapperForKey($key);

        parent::setAttribute($key, $value);
    }

    /**
     * Taken from Guzzle library (guzzlephp.org)
     * Wrapper for JSON decode that implements error detection with helpful
     * error messages.
     *
     * @param string $json    JSON data to parse
     * @param bool   $assoc   When true, returned objects will be converted
     *                        into associative arrays.
     * @param int    $depth   User specified recursion depth.
     * @param int    $options Bitmask of JSON decode options.
     *
     * @return mixed
     * @throws InvalidArgumentException if the JSON cannot be parsed.
     * @link http://www.php.net/manual/en/function.json-decode.php
     */
    protected function exceptionalJsonDecode($json, $assoc = false, $depth = 512, $options = 0)
    {
        static $jsonErrors = [
            JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
            JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded',
        ];

        $data = \json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $last = json_last_error();
            throw new InvalidArgumentException(
                'Unable to parse JSON data: '
                .(isset($jsonErrors[$last])
                    ? $jsonErrors[$last]
                    : 'Unknown error')
            );
        }

        return $data;
    }

    /**
     * @param $key
     *
     * @return ArrayAttributeWrapper
     */
    private function setWrapperForKey($key)
    {
        $wrapper = new ArrayAttributeWrapper(
            $this,
            $key,
            $this->exceptionalJsonDecode(parent::getAttribute($key), true)
        );

        $this->jsonAttrWrappers[$key] = $wrapper;

        return $wrapper;
    }
}
