<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Eft;
use Ebanx\Benjamin\Services\Http\Client;

class EftTest extends GatewayTestCase
{
    public function testPayment()
    {
        $eftSuccessfulResponse = $this->getEftSuccessfulResponseJson();
        $client = $this->getMockedClient($eftSuccessfulResponse);

        $factory = new BuilderFactory('es_CO');
        $payment = $factory->payment()->eft()->build();
        $gateway = new Eft($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Eft($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::COLOMBIA,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Eft(new Config([
            'baseCurrency' => Currency::COP,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::COLOMBIA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Eft(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getEftSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/simulator\/confirm?hash=592c75608acb71e6f460627e5b8a3b0a9cbe98252139ecf6","payment":{"hash":"592c75608acb71e6f460627e5b8a3b0a9cbe98252139ecf6","pin":"697372181","merchant_payment_code":"897cb9bdc94c45c7ccf6198751b23d7e","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-29 16:24:16","confirm_date":null,"transfer_date":null,"amount_br":"152023.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"2355.1200","currency_ext":"USD","due_date":"2017-06-01","instalments":"1","payment_type_code":"eft","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.co","name":"LUNA GRANADOS","birth_date":"1966-05-24"}},"status":"SUCCESS"}';
    }
}
