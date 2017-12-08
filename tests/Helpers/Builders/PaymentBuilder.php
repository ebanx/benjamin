<?php
namespace Tests\Helpers\Builders;

use Ebanx\Benjamin\Models\Card;
use Faker;
use Ebanx\Benjamin\Models\Bank;
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

    /**
     * @return Payment
     */
    public function build()
    {
        return $this->instance;
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
        $this->instance->dueDate = $this->faker->dateTimeBetween('+1 days', '+3 days');

        return $this;
    }

    public function baloto()
    {
        $this->instance->type = 'baloto';
        $this->instance->dueDate = $this->faker->dateTimeBetween('+1 days', '+3 days');

        return $this;
    }

    public function creditCard($instalmentNumber = 1)
    {
        $this->instance->type = 'creditcard';
        $this->instance->card = $this->faker->cardModel();
        $this->instance->instalments = $instalmentNumber;

        return $this;
    }

    public function emptyCreditCard($instalmentNumber = 1)
    {
        $this->instance->type = 'creditcard';
        $this->instance->card = new Card();
        $this->instance->instalments = $instalmentNumber;

        return $this;
    }

    public function debitCard()
    {
        $this->instance->type = 'debitcard';
        $this->instance->card = $this->faker->cardModel();
        $this->instance->card->number = '4242424242424242';

        return $this;
    }

    public function tef()
    {
        $this->instance->type = 'tef';
        $this->instance->bankCode = Bank::BANCO_DO_BRASIL;

        return $this;
    }

    public function eft()
    {
        $this->instance->type = 'eft';
        $this->instance->bankCode = 'banco_gnb_sudameris';

        return $this;
    }

    public function flow()
    {
        $this->instance->type = 'flowcl';
        $this->instance->flow_payment_method = 'webpay';

        return $this;
    }
}
