<?php namespace FHTeam\EloquentCustomAttrs;

use Eloquent;

class AbstractAttributeWrapper
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
     * @var mixed The value, associated with the attribute
     */
    protected $value;

    /**
     * @param Eloquent $object    The associated model
     * @param string   $attribute The name of the attribute of the associated model
     * @param mixed    $value     The value, associated with the attribute
     */
    public function __construct(Eloquent $object, $attribute, $value)
    {
        $this->object = $object;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * Updates associated model using the new value of the attribute
     */
    protected function refreshConnectedModel()
    {
        $this->object->{$this->attribute} = $this->value;
    }
}
