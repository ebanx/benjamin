<?php
namespace Tests\Helpers\Builders;

use Faker;
use Ebanx\Benjamin\Models\Payment;
use Tests\Helpers\Providers;

class BuilderFactory
{
    private $lang;

    private $fakerLang;
    private $faker;

    /**
     * @param string $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param  Payment $instance Optional pre-built instance
     * @return PaymentBuilder
     */
    public function payment(Payment $instance = null)
    {
        return new PaymentBuilder($this->setupFaker(), $instance);
    }

    /**
     * @param string $lang
     *
     * @return BuilderFactory
     */
    public function withLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return Faker\Generator
     */
    private function setupFaker()
    {
        if (!$this->faker || $this->fakerLang != $this->lang) {
            $this->fakerLang = $this->lang;
            $this->faker = Faker\Factory::create(self::convertLangToFakerLang($this->lang));
            $this->faker->addProvider(new Providers\CurrencyCode($this->faker));
            $this->faker->addProvider(new Providers\Item($this->faker));
            $this->faker->addProvider(new Providers\Payment($this->faker));
            $this->faker->addProvider(new Providers\Person($this->faker));
            $this->faker->addProvider(new Providers\Card($this->faker));

            $documentProviderClass = 'Tests\Helpers\Providers\\'.$this->lang.'\Document';
            $this->faker->addProvider(new $documentProviderClass($this->faker));

            $addressProviderClass = 'Tests\Helpers\Providers\\'.$this->lang.'\Address';
            $this->faker->addProvider(new $addressProviderClass($this->faker));
        }
        $this->faker->seed('ebanx');

        return $this->faker;
    }

    private static function convertLangToFakerLang($lang)
    {
        if ($lang === 'pt_BR') {
            return $lang;
        }
        return 'es_ES';
    }
}
