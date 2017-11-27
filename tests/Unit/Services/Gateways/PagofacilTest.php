<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Pagofacil;
use Ebanx\Benjamin\Services\Http\Client;

class PagofacilTest extends GatewayTestCase
{
    public function testPayment()
    {
        $pagofacilSuccessfulResponse = $this->getPagofacilSuccessfulResponseJson();
        $client = $this->getMockedClient($pagofacilSuccessfulResponse);

        $factory = new BuilderFactory('es_AR');
        $payment = $factory->payment()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Pagofacil($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Pagofacil(new Config([
            'baseCurrency' => Currency::ARS,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Pagofacil(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanx.com/print/voucher/?hash=59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575',
            $gateway->getUrl('59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com/print/voucher/?hash=59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575',
            $gateway->getUrl('59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        // TODO: assert something better
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575')
        );
    }

    public function getPagofacilSuccessfulResponseJson()
    {
        return '{"redirect_url":null,"payment":{"hash":"59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575","pin":"617869683","merchant_payment_code":"825c2c20353d1ec16c422a4ee9f6415f","order_number":null,"status":"PE","status_date":null,"open_date":"2017-10-10 22:22:39","confirm_date":null,"transfer_date":null,"amount_br":"196.99","amount_ext":"52.39","amount_iof":"0.00","currency_rate":"3.7600","currency_ext":"USD","due_date":"2017-10-13","instalments":"1","payment_type_code":"pagofacil","voucher_url":"https:\/\/sandbox.ebanx.com\/print\/voucher\/execute?hash=59dd4830ba89b516ba301a73a18c0f5cc09d58660a888575","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.ar","name":"LUNA GRANADOS","birth_date":"1966-10-05"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return PagofacilForTests
     */
    private function getTestGateway($client = null)
    {
        $gateway = new Pagofacil($this->config, $client);
        return $gateway;
    }
}
