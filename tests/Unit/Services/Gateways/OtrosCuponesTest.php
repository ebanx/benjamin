<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\OtrosCupones;
use Ebanx\Benjamin\Services\Http\Client;

class OtrosCuponesTest extends GatewayTestCase
{
    public function testPayment()
    {
        $cuponSuccessfulResponse = $this->getOtrosCuponesSuccessfulResponseJson();
        $client = $this->getMockedClient($cuponSuccessfulResponse);

        $factory = new BuilderFactory('es_AR');
        $payment = $factory->payment()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new OtrosCupones($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new OtrosCupones(new Config([
            'baseCurrency' => Currency::ARS,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new OtrosCupones(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanx.com/print/voucher/?hash=59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8',
            $gateway->getUrl('59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com/print/voucher/?hash=59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8',
            $gateway->getUrl('59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        // TODO: assert something better
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8')
        );
    }

    public function getOtrosCuponesSuccessfulResponseJson()
    {
        return '{"redirect_url":null,"payment":{"hash":"59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8","pin":"235141564","merchant_payment_code":"1733c732892e95a806002a4147f3f1ee","order_number":null,"status":"PE","status_date":null,"open_date":"2017-10-10 22:29:57","confirm_date":null,"transfer_date":null,"amount_br":"196.99","amount_ext":"52.39","amount_iof":"0.00","currency_rate":"3.7600","currency_ext":"USD","due_date":"2017-10-13","instalments":"1","payment_type_code":"cupon","voucher_url":"https:\/\/sandbox.ebanx.com\/print\/voucher\/execute?hash=59dd49e565e80d37b1995a9dfa2767e2494060237b13c3b8","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.ar","name":"LUNA GRANADOS","birth_date":"1966-10-05"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return OtrosCuponesForTests
     */
    private function getTestGateway($client = null)
    {
        $gateway = new OtrosCupones($this->config, $client);
        return $gateway;
    }
}
