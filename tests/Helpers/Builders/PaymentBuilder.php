<?php
namespace Tests\Helpers\Builders;

use Faker;
use Ebanx\Benjamin\Models\Payment;

class PaymentBuilder extends BaseBuilder
{
    public function __construct(Faker\Generator $faker, Payment $instance = null)
    {
        if (!$instance) {
            $instance = $faker->paymentModel();
        }

        parent::__construct($faker, $instance);
    }

    public function businessPerson()
    {
        $this->instance->person = $this->faker->businessPersonModel();
        $this->instance->responsible = $this->faker->personModel();

        return $this;
    }

    public function boleto()
    {
        $this->instance->dueDate = $this->faker->dateTimeBetween('+1 days', '+3 days');

        return $this;
    }
}
