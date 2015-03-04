eloquent-custom-attributes [![Code Climate](https://codeclimate.com/github/fhteam/eloquent-custom-attributes/badges/gpa.svg)](https://codeclimate.com/github/fhteam/eloquent-custom-attributes) [![Laravel compatibility](https://img.shields.io/badge/laravel-4-green.svg)](http://laravel.com/) [![Laravel compatibility](https://img.shields.io/badge/laravel-5-green.svg)](http://laravel.com/)
============


Installation
------------

 - Simple composer installation is ok: ```composer require fhteam/eloquent-custom-attributes:dev-master ```
 (set version requirement to your favourite)
 
Usage
------------

 - Add `CustomAttributeHelperTrait` to your model and any number of various attribute handler traits to your model
 - Define `$customAttributes` property and list there attribute names together with classes, responsible for their
    handling
 - Override Eloquent's `getAttribute` and `setAttribute` methods so that they work with yout custom attributes
 - After that work with your attribute just you are used to. See the example below

```php

/**
 * @property array $json_meta_data This attribute is actually of 'string' type in database and in model. 
 *                                 But we can work with it just like it is array delegating all underlying 
 *                                 operations to JsonAttrHandlerTrait
 */
class Invoice extends Eloquent
{
    use CustomAttributeHelperTrait;
    use JsonAttrHandlerTrait;

    protected $customAttributes = [
        'json_meta_data' => JsonAttrHandlerTrait::class,
    ];

    public function getAttribute($key)
    {
        if ($this->isCustomAttribute($key)) {
            return $this->getCustomAttribute($key);
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($this->isCustomAttribute($key)) {
            $this->setCustomAttribute($key, $value);

            return;
        }

        parent::setAttribute($key, $value);
    }
    
/**
 * Just some simple class to show actual work with model ;)
 */
class Main
{
    public function main() {
        $invoice = Invoice::findOrFail(1);
        $invoice->json_meta_data = ['key' => 'value'];
        $invoice->json_meta_data['key_next'] = 'value_next';
        
        $invoice->save();
    }
}
```

Extending
------------

Should you want to implement your own custom attribute handler, you have just to implement the trait, responsible for
value conversion

 - Naming convention for such traits is `<typeName>AttrHandlerTrait`
 - Attribute handling traits must implement two methods: `handleGetAttribute<typeName>` and `handleSetAttribute<typeName>`.
 Check \FHTeam\EloquentCustomAttrs\Json\JsonAttrHandlerTrait for example
 - Be careful with method naming in traits. Don't make them too common since they must nicely co-exist with methods
 of other traits Eloquent model may use
 - If your custom attribute represents complex type (not just a string or a number) consider making a wrapper around it. An 
 example of such wrapper can be \FHTeam\EloquentCustomAttrs\ArrayAttributeWrapper. It encapsulates an array value,
 provides access to it and updates corresponding model whenever attribute changes. Wrapper is cached so attribute can be 
 accessed several times from any part of the script