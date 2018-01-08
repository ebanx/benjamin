<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\SafetyPayOnline;
use Ebanx\Benjamin\Services\Http\Client;

class SafetyPayOnlineTest extends GatewayTestCase
{
    public function testPayment()
    {
        $safetyPayOnlineSuccessfulResponse = $this->getSafetyPayOnlineSuccessfulResponseJson();
        $client = $this->getMockedClient($safetyPayOnlineSuccessfulResponse);

        $factory = new BuilderFactory('es_PE');
        $payment = $factory->payment()->build();
        $gateway = new SafetyPayOnline($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new SafetyPayOnline($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::ECUADOR,
            Country::PERU,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new SafetyPayOnline(new Config([
            'baseCurrency' => Currency::PEN,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::PERU,
        ]);

        $gateway = new SafetyPayOnline(new Config([
            'baseCurrency' => Currency::USD,
        ]));

        $this->assertTrue($gateway->isAvailableForCountry(Country::ECUADOR));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new SafetyPayOnline(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getSafetyPayOnlineSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/simulator\/confirm?hash=592efc0d3532803c93dce20cfe8850ada40ae1b0cafca009","payment":{"hash":"592efc0d3532803c93dce20cfe8850ada40ae1b0cafca009","pin":"429011940","merchant_payment_code":"3fbf7553619150657708fca8e4bc217d","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-31 14:23:24","confirm_date":null,"transfer_date":null,"amount_br":"261.96","amount_ext":"52.39","amount_iof":"0.01","currency_rate":"5.0000","currency_ext":"USD","due_date":"2017-06-03","instalments":"1","payment_type_code":"safetypay-online","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.pe","name":"LUNA GRANADOS","birth_date":"1966-05-26"}},"status":"SUCCESS"}';
    }
}
