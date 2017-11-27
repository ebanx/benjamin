<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Rapipago;
use Ebanx\Benjamin\Services\Http\Client;

class RapipagoTest extends GatewayTestCase
{
    public function testPayment()
    {
        $rapipagoSuccessfulResponse = $this->getRapipagoSuccessfulResponseJson();
        $client = $this->getMockedClient($rapipagoSuccessfulResponse);

        $factory = new BuilderFactory('es_AR');
        $payment = $factory->payment()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Rapipago($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Rapipago(new Config([
            'baseCurrency' => Currency::ARS,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Rapipago(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanx.com/print/voucher/?hash=59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e',
            $gateway->getUrl('59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com/print/voucher/?hash=59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e',
            $gateway->getUrl('59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        // TODO: assert something better
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e')
        );
    }

    public function getRapipagoSuccessfulResponseJson()
    {
        return '{"redirect_url":null,"payment":{"hash":"59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e","pin":"841604151","merchant_payment_code":"d5ce2d8b700f263e3edae156330b44a1","order_number":null,"status":"PE","status_date":null,"open_date":"2017-10-10 22:05:02","confirm_date":null,"transfer_date":null,"amount_br":"196.99","amount_ext":"52.39","amount_iof":"0.00","currency_rate":"3.7600","currency_ext":"USD","due_date":"2017-10-13","instalments":"1","payment_type_code":"rapipago","voucher_url":"https:\/\/sandbox.ebanx.com\/print\/voucher\/execute?hash=59dd440f947b5097c8f6985c6a5cb71f935d80f745f37d5e","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.ar","name":"LUNA GRANADOS","birth_date":"1966-10-05"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return RapipagoForTests
     */
    private function getTestGateway($client = null)
    {
        $gateway = new Rapipago($this->config, $client);
        return $gateway;
    }
}
