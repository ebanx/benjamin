<?php

namespace Tests\Unit\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Wallet\PayPal;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\Unit\Services\Gateways\WalletTestCase;

class PayPalTest extends WalletTestCase
{
    public function testPayment()
    {
        $walletSuccessfulResponse = $this->getDigitalWalletSuccessfulResponseJson();
        $client = $this->getMockedClient($walletSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->wallet()->build();
        $gateway = new PayPal($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('redirect_url', $result);
        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $gateway = new PayPal($this->config);
        $expectedCountries = [
            Country::BRAZIL,
        ];

        $this->assertAvailableForCountries($gateway, $expectedCountries);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new PayPal(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new PayPal(new Config([
            'baseCurrency' => Currency::BOB,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }
}
