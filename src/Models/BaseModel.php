<?php
namespace Ebanx\Benjamin\Models;

abstract class BaseModel
{
    /**
     * Fill the object with the provided $attributes array
     *
     * @param $attributes array
     */
    public function __construct($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}
