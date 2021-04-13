<?php

namespace Tests\Unit\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Wallet\MercadoPago;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\Unit\Services\Gateways\WalletTestCase;

class MercadoPagoTest extends WalletTestCase
{
    public function testPayment()
    {
        $walletSuccessfulResponse = $this->getDigitalWalletSuccessfulResponseJson();
        $client = $this->getMockedClient($walletSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->wallet()->build();
        $gateway = new MercadoPago($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('redirect_url', $result);
        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $gateway = new MercadoPago($this->config);
        $expectedCountries = [
            Country::ARGENTINA,
            Country::BRAZIL,
            Country::MEXICO
        ];

        $this->assertAvailableForCountries($gateway, $expectedCountries);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new MercadoPago(new Config([
            'baseCurrency' => Currency::ARS,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA
        ]);

        $gateway = new MercadoPago(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL
        ]);

        $gateway = new MercadoPago(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new MercadoPago(new Config([
            'baseCurrency' => Currency::BOB,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }
}
