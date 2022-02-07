<?php

namespace Tests\Unit\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Wallet\MACHPay;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\Unit\Services\Gateways\WalletTestCase;

class MACHPayTest extends WalletTestCase
{
    public function testPayment()
    {
        $walletSuccessfulResponse = $this->getDigitalWalletSuccessfulResponseJson();
        $client = $this->getMockedClient($walletSuccessfulResponse);

        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->wallet()->build();
        $gateway = new MACHPay($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('redirect_url', $result);
        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $gateway = new MACHPay($this->config);
        $expectedCountries = [
            Country::CHILE,
        ];

        $this->assertAvailableForCountries($gateway, $expectedCountries);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new MACHPay(new Config([
            'baseCurrency' => Currency::CLP,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::CHILE,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new MACHPay(new Config([
            'baseCurrency' => Currency::BOB,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }
}
