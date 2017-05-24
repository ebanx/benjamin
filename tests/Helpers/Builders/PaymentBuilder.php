<?php
namespace Tests\Helpers\Builders;

use Ebanx\Benjamin\Models\Currency;
use Faker;
use Ebanx\Benjamin\Models\Payment;

class PaymentBuilder extends BaseBuilder
{
    /**
     * @var Payment
     */
    protected $instance;

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
        $this->instance->type = 'boleto';
        $this->instance->currencyCode = Currency::BRL;
        $this->instance->dueDate = $this->faker->dateTimeBetween('+1 days', '+3 days');

        return $this;
    }

    public function creditCard($instalmentNumber = 1)
    {
        $this->instance->type = 'creditcard';
        $this->instance->currencyCode = Currency::BRL;
        $this->instance->card = $this->faker->cardModel();
        $this->instance->instalments = $instalmentNumber;

        return $this;
    }

    public function oxxo()
    {
        $this->instance->type = 'oxxo';
        $this->instance->currencyCode = Currency::USD;

        return $this;
    }

    public function baloto()
    {
        $this->instance->type = 'baloto';
        $this->instance->currencyCode = Currency::USD;

        return $this;
    }
}
