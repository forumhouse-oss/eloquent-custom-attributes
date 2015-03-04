<?php

namespace FHTeam\EloquentCustomAttrs;

use Exception;
use Str;

/**
 * Custom attribute helper for Eloquent models
 *
 * @property-read array $customAttributes The list of the attributes to handle:
 *                ['propertyName' => 'HandlingClassName']
 * @package       FHTeam\EloquentCustomAttrs
 */
trait CustomAttributeHelperTrait
{
    /**
     * Determines if an attribute with a given name should be handled by this trait.
     * This should be called in getAttribute() override in your model to check if you need to call
     * getCustomAttribute() / setCustomAttribute() or forward the call to the parent
     *
     * @param string $key
     *
     * @return bool
     */
    public function isCustomAttribute($key)
    {
        return isset($this->customAttributes[$key]);
    }

    /**
     * Gets custom attribute
     *
     * @param string $key The name of the attribute to get
     *
     * @return mixed
     */
    public function getCustomAttribute($key)
    {
        $methodName = $this->getCustomAttrAccessorMethod($key);
        return $this->$methodName($key);
    }

    /**
     * Sets custom attribute
     *
     * @param string $key   The name of the attribute to set
     * @param mixed  $value The value of the attribute to set
     */
    public function setCustomAttribute($key, $value)
    {
        $methodName = $this->getCustomAttrMutatorMethod($key);
        $this->$methodName($key, $value);
    }

    /**
     * Returns the name of the trait method suitable for reading custom attribute
     *
     * @param string $key
     *
     * @return string
     * @throws Exception
     */
    protected function getCustomAttrAccessorMethod($key)
    {
        return $this->getCustomAttrHandlerMethodName($key, 'Get');
    }

    /**
     * Returns the name of the trait method suitable for writing custom attribute
     *
     * @param string $key
     *
     * @return string
     * @throws Exception
     */
    protected function getCustomAttrMutatorMethod($key)
    {
        return $this->getCustomAttrHandlerMethodName($key, 'Set');
    }

    /**
     * Returns the name of the method, that handles attribute access or mutation
     *
     * @param string $key  The name of the attribute
     * @param string $type 'Get' or 'Set'
     *
     * @return string
     * @throws Exception
     */
    protected function getCustomAttrHandlerMethodName($key, $type)
    {
        $methodName = class_basename($this->customAttributes[$key]);

        if (!Str::endsWith($methodName, 'AttrHandlerTrait')) {
            throw new Exception(
                "Attribute handler trait name should have end with 'AttrHandlerTrait'. Name given: $methodName"
            );
        }
        $methodName = substr($methodName, 0, -strlen('AttrHandlerTrait'));
        $methodName = "handle{$type}Attribute" . $methodName;

        if (!method_exists($this, $methodName)) {
            throw new Exception("Accessor or mutator for field '$key' does not exists. Expected '$methodName'");
        }

        return $methodName;
    }
}
