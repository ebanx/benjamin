<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Gateways\Oxxo;
use Ebanx\Benjamin\Services\Http\Client;

class OxxoTest extends GatewayTestCase
{
    public function testPayment()
    {
        $oxxoSuccessfulResponse = $this->getOxxoSuccessfulResponseJson();
        $client = $this->getMockedClient($oxxoSuccessfulResponse);

        $mxFactory = BuilderFactory::lang('es_MX');
        $payment = $mxFactory::payment()->oxxo()->build();
        $gateway = new OxxoForTests($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function getOxxoSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e","pin":"484657684","merchant_payment_code":"c15a03ecdea06d3c55db001af76c6186","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-23 20:32:40","confirm_date":null,"transfer_date":null,"amount_br":"1362.01","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"21.1000","currency_ext":"USD","due_date":"2017-05-26","instalments":"1","payment_type_code":"oxxo","oxxo_url":"https:\/\/sandbox.ebanx.com\/print\/oxxo\/?hash=5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e","oxxo_barcode":"51000000000020022017052601362015","pre_approved":false,"capture_available":null,"note":"Fake payment created by PHPUnit.","customer":{"document":"932221309","email":"alfaro.mara@loya.es","name":"LUNA GRANADOS","birth_date":"1966-05-18"}},"status":"SUCCESS"}';
    }
}

class OxxoForTests extends Oxxo
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
