<?php
namespace Tests\Helpers\Builders;

use Ebanx\Benjamin\Models\Payment;
use Tests\Helpers\Providers;
use Faker;

class BuilderFactory
{
    private static $faker;

    public static function payment(Payment $instance = null)
    {
        return new PaymentBuilder(self::setupFaker(), $instance);
    }

    private static function setupFaker()
    {
        if (!self::$faker) {
            self::$faker = Faker\Factory::create('pt_BR');
            self::$faker->addProvider(new Providers\Address(self::$faker));
            self::$faker->addProvider(new Providers\CurrencyCode(self::$faker));
            self::$faker->addProvider(new Providers\Item(self::$faker));
            self::$faker->addProvider(new Providers\Payment(self::$faker));
            self::$faker->addProvider(new Providers\Person(self::$faker));
        }
        self::$faker->seed('ebanx');

        return self::$faker;
    }
}
