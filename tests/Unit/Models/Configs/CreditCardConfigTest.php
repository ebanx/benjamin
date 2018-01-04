<?php
namespace Tests\Unit\Models\Configs;

use Tests\TestCase;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Services\Gateways\CreditCard;
use Ebanx\Benjamin\Models\Currency;

class CreditCardConfigTest extends TestCase
{
    public function testBuildInsterestRates()
    {
        $ccConfig = new CreditCardConfig();
        $ccConfig
            ->addInterest(1, 0.02)
            ->addInterest(2, 0.06)
            ->addInterest(3, 0.10)
            ->addInterest(4, 0.15);

        $this->assertEquals(4, count($ccConfig->interestRates));
    }

    public function testAcquirerMinInstalmentValueForCurrency()
    {
        $countries = CreditCardForTests::getEnabledCountries();
        foreach ($countries as $country) {
            $currency = Currency::localForCountry($country);
            $this->assertNotNull(
                CreditCardConfig::acquirerMinInstalmentValueForCurrency($currency),
                'CreditCardConfig has no acquirer minimum instalment value set for '.$currency
            );
        }
    }

    public function testAcquirerMinInstalmentValueForCurrencyWithInvalidCode()
    {
        $this->assertNull(
            CreditCardConfig::acquirerMinInstalmentValueForCurrency('AAA'),
            'Invalid currency should not have a value'
        );
    }
}

class CreditCardForTests extends CreditCard
{
    public static function getEnabledCountries()
    {
        return parent::getEnabledCountries();
    }
}
