<?php

namespace FHTeam\EloquentCustomAttrs;

use Arr;
use ArrayAccess;
use ArrayIterator;
use Countable;
use Eloquent;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Wrapper around plain array that allows to update underlying model whenever some part of delegated array changes
 *
 * @package FHTeam\EloquentCustomAttrs
 */
class ArrayAttributeWrapper implements ArrayAccess, JsonSerializable, IteratorAggregate, Arrayable, Countable
{
    /**
     * @var Eloquent The associated model
     */
    protected $object;

    /**
     * @var string The name of the attribute of the associated model
     */
    protected $attribute;
    /**
     * @var array The value, associated with the attribute
     */
    protected $value;

    /**
     * @param Eloquent $object    The associated model
     * @param string   $attribute The name of the attribute of the associated model
     * @param array    $value     The value, associated with the attribute
     */
    public function __construct(Eloquent $object, $attribute, $value)
    {
        $this->object = $object;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return null !== Arr::get($this->value, $offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return Arr::get($this->value, $offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->value[] = $value;
        } else {
            Arr::set($this->value, $offset, $value);
        }
        $this->refreshConnectedModel();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        Arr::forget($this->value, $offset);
        $this->refreshConnectedModel();
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *       which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *       <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->value);
    }

    /**
     * Updates associated model using the new value of the attribute
     */
    protected function refreshConnectedModel()
    {
        $this->object->{$this->attribute} = $this->value;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->value);
    }
}
