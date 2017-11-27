<?php
namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Gateways\DirectGateway;
use Ebanx\Benjamin\Services\Http\Client;
use Tests\TestCase;

class DirectGatewayTest extends TestCase
{
    public function testGatewayOnLiveMode()
    {
        $config = new Config(['isSandbox' => false]);
        $gateway = new TestGateway($config);

        $this->assertEquals(Client::MODE_LIVE, $gateway->getClient()->getMode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowWithInvalidCountry()
    {
        $gateway = new TestGateway(new Config());
        $gateway->countryNotAvailable();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testEnabledCountriesGetterNotOverridden()
    {
        NoCountryNoCurrencyGateway::acceptsCountry('test');
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testEnabledCurrenciesGetterNotOverridden()
    {
        NoCountryNoCurrencyGateway::acceptsCurrency('test');
    }
}

class NoCountryNoCurrencyGateway extends DirectGateway
{
    protected function getPaymentData(Payment $payment)
    {
        return;
    }
}

class TestGateway extends DirectGateway
{
    public function getClient()
    {
        return $this->client;
    }

    public function countryNotAvailable()
    {
        $this->availableForCountryOrThrow('invalidCountry');
    }

    protected function getPaymentData(Payment $payment)
    {
        return;
    }

    protected static function getEnabledCountries()
    {
        return [];
    }

    protected static function getEnabledCurrencies()
    {
        return [];
    }
}
