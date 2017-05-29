<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Servipag;
use Ebanx\Benjamin\Services\Http\Client;

class ServipagTest extends GatewayTestCase
{
    public function testPayment()
    {
        $servipagSuccessfulResponse = $this->getServipagSuccessfulResponseJson();
        $client = $this->getMockedClient($servipagSuccessfulResponse);

        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->servipag()->build();
        $gateway = new ServipagForTests($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Servipag($this->config);

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Servipag(new Config(array(
            'baseCurrency' => Currency::COP
        )));

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Servipag(new Config(array(
            'baseCurrency' => Currency::MXN
        )));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getServipagSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/simulator\/confirm?hash=592c75608acb71e6f460627e5b8a3b0a9cbe98252139ecf6","payment":{"hash":"592c75608acb71e6f460627e5b8a3b0a9cbe98252139ecf6","pin":"697372181","merchant_payment_code":"897cb9bdc94c45c7ccf6198751b23d7e","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-29 16:24:16","confirm_date":null,"transfer_date":null,"amount_br":"152023.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"2355.1200","currency_ext":"USD","due_date":"2017-06-01","instalments":"1","payment_type_code":"servipag","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.co","name":"LUNA GRANADOS","birth_date":"1966-05-24"}},"status":"SUCCESS"}';
    }
}

class ServipagForTests extends Servipag
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
