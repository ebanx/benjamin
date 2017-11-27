<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Sencillito;
use Ebanx\Benjamin\Services\Http\Client;

class SencillitoTest extends GatewayTestCase
{
    public function testPayment()
    {
        $sencillitoSuccessfulResponse = $this->getSencillitoSuccessfulResponseJson();
        $client = $this->getMockedClient($sencillitoSuccessfulResponse);

        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->build();
        $gateway = new Sencillito($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Sencillito($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::CHILE,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Sencillito(new Config([
            'baseCurrency' => Currency::CLP,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::CHILE,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Sencillito(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getSencillitoSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/simulator\/confirm?hash=59260b09b9d3b15e2c5b42b42f3752ce2225d6f27f686236","payment":{"hash":"59260b09b9d3b15e2c5b42b42f3752ce2225d6f27f686236","pin":"020330756","merchant_payment_code":"90bf954f559b30eb710fb0f49df23f2d","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-24 19:36:56","confirm_date":null,"transfer_date":null,"amount_br":"44933.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"696.0900","currency_ext":"USD","due_date":"2017-05-27","instalments":"1","payment_type_code":"sencillito","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"932221309","email":"alfaro.mara@loya.es.cl","name":"LUNA GRANADOS","birth_date":"1966-05-19"}},"status":"SUCCESS"}';
    }
}
