<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Address as AddressModel;

class Address extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\Address
     */
    public function addressModel()
    {
        $address = new AddressModel();
        $address->address = $this->faker->streetName;
        $address->city = $this->faker->city;
        $address->country = Country::BRAZIL;
        $address->state = $this->faker->stateAbbr();
        $address->streetComplement = $this->faker->secondaryAddress();
        $address->streetNumber = $this->faker->buildingNumber;
        $address->zipcode = $this->faker->postcode;

        return $address;
    }
}
