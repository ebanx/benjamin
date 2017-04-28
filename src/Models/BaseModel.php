<?php
namespace Ebanx\Benjamin\Models;

abstract class BaseModel
{
    public function __construct($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists(self, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}
