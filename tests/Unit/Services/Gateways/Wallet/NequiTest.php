<?php

namespace Tests\Unit\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Wallet\Nequi;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\Unit\Services\Gateways\WalletTestCase;

class NequiTest extends WalletTestCase
{
    public function testPayment()
    {
        $walletSuccessfulResponse = $this->getDigitalWalletSuccessfulResponseJson();
        $client = $this->getMockedClient($walletSuccessfulResponse);

        $factory = new BuilderFactory('es_CO');
        $payment = $factory->payment()->wallet()->build();
        $gateway = new Nequi($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('redirect_url', $result);
        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $gateway = new Nequi($this->config);
        $expectedCountries = [
            Country::COLOMBIA,
        ];

        $this->assertAvailableForCountries($gateway, $expectedCountries);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Nequi(new Config([
            'baseCurrency' => Currency::COP,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::COLOMBIA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Nequi(new Config([
            'baseCurrency' => Currency::BOB,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }
}
