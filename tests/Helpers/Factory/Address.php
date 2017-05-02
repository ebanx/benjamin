<?php

namespace Tests\Helpers\Factory;

use Ebanx\Benjamin\Models\Address as AddressModel;

class Address extends BaseFactory
{
    public static function valid()
    {
        $faker = self::faker();

        $address = new AddressModel();
        $address->address = $faker->streetName;
        $address->city = $faker->city;
        $address->country = 'Brasil';
        $address->state = $faker->state();
        $address->streetComplement = $faker->secondaryAddress();
        $address->streetNumber = $faker->buildingNumber;
        $address->zipcode = $faker->postcode;

        return $address;
    }
}
