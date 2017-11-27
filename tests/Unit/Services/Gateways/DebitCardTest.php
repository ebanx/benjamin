<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;

use Ebanx\Benjamin\Services\Gateways\DebitCard;
use Ebanx\Benjamin\Services\Http\Client;

class DebitCardTest extends GatewayTestCase
{
    public function testPayment()
    {
        $creditCardSuccessfulResponse = $this->getDebitCardSuccessfulResponseJson();
        $client = $this->getMockedClient($creditCardSuccessfulResponse);

        $factory = new BuilderFactory('es_MX');
        $payment = $factory->payment()->debitCard()->build();
        $gateway = new DebitCard($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSDEUR()
    {
        $gateway = new DebitCard($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO,
        ]);

        $gateway = new DebitCard(new Config([
            'baseCurrency' => Currency::EUR,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new DebitCard(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new DebitCard(new Config([
            'baseCurrency' => Currency::CLP,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    private function getDebitCardSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"592c955dbc4e2d9afcf3f0ba558b3d87e209d49ca57597ea","pin":"403031613","merchant_payment_code":"6a9b9bba03c0ab16c4ee8fb14473d1fe","order_number":null,"status":"CO","status_date":"2017-05-29 18:40:44","open_date":"2017-05-29 18:40:44","confirm_date":"2017-05-29 18:40:44","transfer_date":null,"amount_br":"1362.01","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"21.1000","currency_ext":"USD","due_date":"2017-06-01","instalments":"1","payment_type_code":"debitcard","transaction_status":{"acquirer":"EBANX","code":"OK","description":"Sandbox - Test debit card, transaction captured"},"pre_approved":true,"capture_available":false,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"932221309","email":"alfaro.mara@loya.es.mx","name":"LUNA GRANADOS","birth_date":"1966-05-24"}},"status":"SUCCESS"}';
    }
}
