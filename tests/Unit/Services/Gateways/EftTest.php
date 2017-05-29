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
        $gateway = new EftForTests($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Eft($this->config);

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Eft(new Config(array(
            'baseCurrency' => Currency::COP
        )));

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Eft(new Config(array(
            'baseCurrency' => Currency::MXN
        )));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getEftSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/directtefredirect\/?hash=5928c60eaa72a35e9f6518d84d35fb270e0a79718fc1feb9","payment":{"hash":"5928c60eaa72a35e9f6518d84d35fb270e0a79718fc1feb9","pin":"214657893","merchant_payment_code":"145bcbe3ac952833ff122fb50e9f00d1","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-26 21:19:26","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"USD","due_date":"2017-05-29","instalments":"1","payment_type_code":"ebanxaccount","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"04540945076","email":"luana79@serra.net.br","name":"SR THIAGO VIEIRA NETO","birth_date":"1955-01-25"}},"status":"SUCCESS"}';
    }
}

class EftForTests extends Eft
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
