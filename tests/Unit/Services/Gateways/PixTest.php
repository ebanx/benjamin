<?php

namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Pix;
use Tests\Helpers\Builders\BuilderFactory;

class PixTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $pixSuccessfulResponse = $this->getPixSuccessfulResponseJson();
        $client = $this->getMockedClient($pixSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->pix()->businessPerson()->build();
        $gateway = new Pix($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Pix($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Pix(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Pix(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getPixSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"6006eda8694708f8032b19911e86d4a904cfe8e9b35303bd","pin":"067483265","country":"br","merchant_payment_code":"8FA1240A6C84","order_number":null,"status":"OP","status_date":null,"open_date":"2021-01-19 14:33:11","confirm_date":null,"transfer_date":null,"amount_br":100.38,"amount_ext":100,"amount_iof":0.38,"currency_rate":1,"currency_ext":"BRL","due_date":"2021-01-22","instalments":1,"payment_type_code":"pix","pre_approved":false,"capture_available":null,"customer":null},"redirect_url":"https://checkout.ebanx.com/checkout?hash=6006eda8694708f8032b19911e86d4a904cfe8e9b35303bd","status":"SUCCESS"}';
    }
}
