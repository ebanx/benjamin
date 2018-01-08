<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Spei;
use Ebanx\Benjamin\Services\Http\Client;

class SpeiTest extends GatewayTestCase
{
    public function testPayment()
    {
        $eftSuccessfulResponse = $this->getSpeiSuccessfulResponseJson();
        $client = $this->getMockedClient($eftSuccessfulResponse);

        $factory = new BuilderFactory('es_MX');
        $payment = $factory->payment()->eft()->build();
        $gateway = new Spei($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Spei($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Spei(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::MEXICO,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Spei(new Config([
            'baseCurrency' => Currency::COP,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanx.com/print/spei/execute?hash=5a53bc54679dbc1f3baf868e9d5d257ee7367fd5c47983fc',
            $gateway->getUrl('5a53bc54679dbc1f3baf868e9d5d257ee7367fd5c47983fc')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com/print/spei/execute?hash=5a53bc54679dbc1f3baf868e9d5d257ee7367fd5c47983fc',
            $gateway->getUrl('5a53bc54679dbc1f3baf868e9d5d257ee7367fd5c47983fc', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        // TODO: assert something better
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('5a53bc54679dbc1f3baf868e9d5d257ee7367fd5c47983fc')
        );
    }

    public function getSpeiSuccessfulResponseJson()
    {
        return '{"redirect_url":null,"payment":{"hash":"59dd3ebed3328435c46b5b1130ec2cf98e94af449d3b6a51","pin":"001540362","merchant_payment_code":"d4bfe053764337a326309a1230294db8","order_number":null,"status":"PE","status_date":null,"open_date":"2017-10-10 21:42:22","confirm_date":null,"transfer_date":null,"amount_br":"2388.73","amount_ext":"113.21","amount_iof":"0.00","currency_rate":"21.1000","currency_ext":"USD","due_date":"2017-10-13","instalments":"1","payment_type_code":"spei","clabe_account":"646181141900000339","clabe_reference":"4262291","spei_url":"https:\/\/sandbox.ebanx.com\/print\/spei\/execute?hash=59dd3ebed3328435c46b5b1130ec2cf98e94af449d3b6a51","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.mx","name":"LUNA GRANADOS","birth_date":"1966-10-05"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return Spei
     */
    private function getTestGateway(Client $client = null)
    {
        $gateway = new Spei($this->config, $client);
        return $gateway;
    }
}
