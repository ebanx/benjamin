<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Baloto;
use Ebanx\Benjamin\Services\Http\Client;

class BalotoTest extends GatewayTestCase
{
    public function testPayment()
    {
        $balotoSuccessfulResponse = $this->getBalotoSuccessfulResponseJson();
        $client = $this->getMockedClient($balotoSuccessfulResponse);

        $factory = new BuilderFactory('es_CO');
        $payment = $factory->payment()->baloto()->build();
        $gateway = new BalotoForTests($this->config, $client);

        $payment->person->email .= '.co';

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Baloto($this->config);

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Baloto(new Config(array(
            'baseCurrency' => Currency::COP
        )));

        $this->assertAvailableForCountries($gateway, array(
            Country::COLOMBIA
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Baloto(new Config(array(
            'baseCurrency' => Currency::MXN
        )));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getBalotoSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"5925f3653b2c75e9ce7568d4f16c6d67648b8e92f7f05fa5","pin":"484939887","merchant_payment_code":"27c251a65a854cfa74d052e66bdac8e8","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-24 17:56:05","confirm_date":null,"transfer_date":null,"amount_br":"152023.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"2355.1200","currency_ext":"USD","due_date":"2017-05-27","instalments":"1","payment_type_code":"baloto","baloto_url":"https:\/\/sandbox.ebanx.com\/print\/baloto\/?hash=5925f3653b2c75e9ce7568d4f16c6d67648b8e92f7f05fa5","voucher_id":"484939887","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"932221309","email":"alfaro.mara@loya.es.co","name":"LUNA GRANADOS","birth_date":"1966-05-19"}},"status":"SUCCESS"}';
    }
}

class BalotoForTests extends Baloto
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
