<?php
namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Services\Gateways\PagosNet;
use Tests\Helpers\Builders\BuilderFactory;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Http\Client;

class PagosNetTest extends GatewayTestCase
{
    public function testPayment()
    {
        $pagosnet_successful_response = $this->getPagosnetSuccesfullResponse();
        $client = $this->getMockedClient($pagosnet_successful_response);

        $factory = new BuilderFactory('es_BO');
        $payment = $factory->payment()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new PagosNet($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::BOLIVIA,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new PagosNet(new Config([
            'baseCurrency' => Currency::BOB,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BOLIVIA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new PagosNet(new Config([
            'baseCurrency' => Currency::CLP,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanxpay.com/print/voucher/execute?hash=5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e',
            $gateway->getUrl('5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanxpay.com/print/voucher/execute?hash=5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e',
            $gateway->getUrl('5924c698895ed4cf3764c681fe9496f8fe0a986b070a594e', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4')
        );
    }

    public function getPagosnetSuccesfullResponse()
    {
        return '{"payment":{"hash":"5d1a51ae18db1261aab3469e598a52cedb4e855d3301235f","pin":"068416154","country":"bo","merchant_payment_code":"bolivia124","order_number":null,"status":"PE","status_date":null,"open_date":"2019-07-01 18:32:13","confirm_date":null,"transfer_date":null,"amount_br":"345.50","amount_ext":"50.00","amount_iof":"0.00","currency_rate":"6.9100","currency_ext":"USD","due_date":"2019-07-02","instalments":"1","payment_type_code":"pagosnet","voucher_url":"https:\/\/sandbox.ebanxpay.com\/print\/voucher\/execute?hash=5d1a51ae18db1261aab3469e598a52cedb4e855d3301235f","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Example payment.","customer":{"document":"40701766000118","email":"sdasneves+bo@r7.com","name":"SR GUSTAVO FERNANDO VALENCIA","birth_date":"1978-03-29"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return PagosNet for tests
     */
    private function getTestGateway(Client $client = null)
    {
        $gateway = new PagosNet($this->config, $client);
        return $gateway;
    }
}
