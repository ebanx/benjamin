<?php

namespace Tests\Helpers\Factory;

use Ebanx\Benjamin\Models\Item as ItemModel;

class Item extends BaseFactory
{
    public static function valid()
    {
        $faker = self::faker();

        $item = new ItemModel();
        $item->sku = $faker->valid(function ($word) {
                //Try again if the generated word is longer than 20
                return strlen($word) <= 20;
            })->word;

        return $item;
    }
}