<?php
namespace Tests\Helpers\Builders;

use Faker;
use Ebanx\Benjamin\Models\Payment;
use Tests\Helpers\Providers;

class BuilderFactory
{
    private static $lang = null;

    private static $fakerLang;
    private static $faker;

    public static function payment(Payment $instance = null)
    {
        return new PaymentBuilder(self::setupFaker(), $instance);
    }

    /**
     * @param string $lang
     *
     * @return BuilderFactory
     */
    public static function lang($lang)
    {
        self::$lang = $lang;

        return '\Tests\Helpers\Builders\BuilderFactory';
    }

    private static function setupFaker()
    {
        if (!self::$lang) {
            // TODO: Make it not static
            throw new \InvalidArgumentException('You need to set a language with lang() before using any factory.');
        }

        if (!self::$faker || self::$fakerLang != self::$lang) {
            self::$fakerLang = self::$lang;
            self::$faker = Faker\Factory::create(self::convertLangToFakerLang(self::$lang));
            self::$faker->addProvider(new Providers\CurrencyCode(self::$faker));
            self::$faker->addProvider(new Providers\Item(self::$faker));
            self::$faker->addProvider(new Providers\Payment(self::$faker));
            self::$faker->addProvider(new Providers\Person(self::$faker));
            self::$faker->addProvider(new Providers\Card(self::$faker));

            $documentProviderClass = 'Tests\Helpers\Providers\\'.self::$lang.'\Document';
            self::$faker->addProvider(new $documentProviderClass(self::$faker));

            $addressProviderClass = 'Tests\Helpers\Providers\\'.self::$lang.'\Address';
            self::$faker->addProvider(new $addressProviderClass(self::$faker));
        }
        self::$faker->seed('ebanx');

        return self::$faker;
    }

    private static function convertLangToFakerLang($lang)
    {
        if ($lang === 'pt_BR') {
            return $lang;
        }
        return 'es_ES';
    }
}
