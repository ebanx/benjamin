<?php
namespace Ebanx\Benjamin\Models;

abstract class BaseModel
{
    /**
     * Fill the object with the provided $attributes array
     *
     * @param $attributes array
     */
    public function __construct($attributes = array())
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @return string
     */
    public function getShortClassname()
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }
}
