<?php
namespace Tests\Helpers\Builders;

use Faker;
use Ebanx\Benjamin\Models\Payment;
use Tests\Helpers\Providers;

class BuilderFactory
{
    private static $lang = "pt_BR";

    private static $fakerLang;
    private static $faker;

    public static function payment(Payment $instance = null)
    {
        return new PaymentBuilder(self::setupFaker(), $instance);
    }

    public static function lang($lang)
    {
        self::$lang = $lang;

        return '\Tests\Helpers\Builders\BuilderFactory';
    }

    private static function setupFaker()
    {
        if (!self::$faker || self::$fakerLang != self::$lang) {
            self::$fakerLang = self::$lang;
            self::$faker = Faker\Factory::create(self::$lang);
            self::$faker->addProvider(new Providers\Address(self::$faker));
            self::$faker->addProvider(new Providers\CurrencyCode(self::$faker));
            self::$faker->addProvider(new Providers\Item(self::$faker));
            self::$faker->addProvider(new Providers\Payment(self::$faker));
            self::$faker->addProvider(new Providers\Person(self::$faker));
            self::$faker->addProvider(new Providers\Card(self::$faker));

            $documentProviderClass = 'Tests\Helpers\Providers\\'.self::$lang.'\Document';
            self::$faker->addProvider(new $documentProviderClass(self::$faker));
        }
        self::$faker->seed('ebanx');

        return self::$faker;
    }
}
