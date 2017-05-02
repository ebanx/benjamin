<?php

namespace Tests\Helpers\Factory;

class CurrencyCode extends BaseFactory
{
    public static function valid()
    {
        $faker = self::faker();
        return $faker->randomElement([
            'USD',
            'EUR',
            'BRL',
            'MXN',
            'PEN',
            'COP',
            'CLP'
        ]);
    }

    public static function invalid()
    {
        return 'ABC';
    }
}