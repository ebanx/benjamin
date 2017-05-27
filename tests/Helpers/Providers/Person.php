<?php
namespace Tests\Helpers\Providers;

use Ebanx\Benjamin\Models\Person as PersonModel;

abstract class Person extends BaseProvider
{
    /**
     * @return \Ebanx\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = new PersonModel();
        $person->birthdate = $this->faker->dateTimeBetween('-65 years', '-18 years');
        $person->email = $this->faker->email;
        $person->ip = $this->faker->ipv4;
        $person->name = $this->faker->name;
        $person->phoneNumber = $this->faker->phoneNumber;
        $person->document = $this->faker->documentNumber(false);

        return $person;
    }

    /**
     * @return \Ebanx\Benjamin\Models\Person
     */
    public function businessPersonModel()
    {
        $person = $this->personModel();
        $person->type = PersonModel::TYPE_BUSINESS;
        $person->document = $this->faker->cnpj(false);

        return $person;
    }
}
