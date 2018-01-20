<?php
namespace Ebanx\Benjamin\Models;

abstract class BaseModel
{
    /**
     * Fill the object with the provided $attributes array
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * Gives you a stdClass object with all attributes snake cased
     *
     * @return void
     */
    public function fetchValues()
    {
        $ref = new \ReflectionClass($this);
        $props = $ref->getProperties(\ReflectionProperty::IS_PUBLIC);

        $obj = new \stdClass();

        foreach ($props as $prop) {
            $obj->{$this->toSnakeCase($prop->getName())} = $prop->getValue($this);
        }

        return $obj;
    }

    /**
     * @return string
     */
    public function getShortClassname()
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    private function toSnakeCase($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = strtolower($match);
        }

        return implode('_', $ret);
    }
}
