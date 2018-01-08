<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\SafetyPayCash;
use Ebanx\Benjamin\Services\Http\Client;

class SafetyPayCashTest extends GatewayTestCase
{
    public function testPayment()
    {
        $safetyPayCashSuccessfulResponse = $this->getSafetyPayCashSuccessfulResponseJson();
        $client = $this->getMockedClient($safetyPayCashSuccessfulResponse);

        $factory = new BuilderFactory('es_PE');
        $payment = $factory->payment()->build();
        $gateway = new SafetyPayCash($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new SafetyPayCash($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::ECUADOR,
            Country::PERU,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new SafetyPayCash(new Config([
            'baseCurrency' => Currency::PEN,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::PERU,
        ]);

        $gateway = new SafetyPayCash(new Config([
            'baseCurrency' => Currency::USD,
        ]));

        $this->assertTrue($gateway->isAvailableForCountry(Country::ECUADOR));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new SafetyPayCash(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getSafetyPayCashSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/simulator\/confirm?hash=592dd4785dd9aaac6868c065581c18a61ed002d73c8bf09b","payment":{"hash":"592dd4785dd9aaac6868c065581c18a61ed002d73c8bf09b","pin":"064849205","merchant_payment_code":"6f40f22e67db8ba64f13d621565c1fe8","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-30 17:22:16","confirm_date":null,"transfer_date":null,"amount_br":"566.08","amount_ext":"113.21","amount_iof":"0.03","currency_rate":"5.0000","currency_ext":"USD","due_date":"2017-06-02","instalments":"1","payment_type_code":"safetypay-cash","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.pe","name":"LUNA GRANADOS","birth_date":"1966-05-25"}},"status":"SUCCESS"}';
    }
}
