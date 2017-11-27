<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Tef;
use Ebanx\Benjamin\Services\Http\Client;

class TefTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $tefSuccessfulResponse = $this->getTefSuccessfulResponseJson();
        $client = $this->getMockedClient($tefSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->tef()->businessPerson()->build();
        $gateway = new Tef($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Tef($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Tef(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Tef(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getTefSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/directtefredirect\/?hash=5928bf451e5a4f07c7b6e1e067d97bdb003b520423134f53","payment":{"hash":"5928bf451e5a4f07c7b6e1e067d97bdb003b520423134f53","pin":"866264009","merchant_payment_code":"611549d83648c36b0d60849f3cba5b05","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-26 20:50:29","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-05-29","instalments":"1","payment_type_code":"bancodobrasil","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"40701766000118","email":"tessalia69@r7.com","name":"SR GUSTAVO FERNANDO VALENCIA","birth_date":"1984-05-12"}},"status":"SUCCESS"}';
    }
}
