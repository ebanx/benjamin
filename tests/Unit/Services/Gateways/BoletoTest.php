<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Boleto;
use Ebanx\Benjamin\Services\Http\Client;
use Tests\Helpers\Mocks\Http\ClientForTests;

class BoletoTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $boletoSuccessfulResponse = $this->getBoletoSuccessfulResponseJson();
        $client = $this->getMockedClient($boletoSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->boleto()->businessPerson()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);
        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testBusinessPersonRequest()
    {
        $boletoSuccessfulResponse = $this->getBoletoSuccessfulResponseJson();
        $client = $this->getMockedClient($boletoSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->boleto()->businessPerson()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->request($payment);
        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Boleto($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Boleto(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Boleto(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://sandbox.ebanx.com/print/?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4',
            $gateway->getUrl('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com/print/?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4',
            $gateway->getUrl('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4', false)
        );
    }

    public function testTicketContent()
    {
        $gateway = $this->getTestGateway($this->getMockedClient('<html></html>'));
        // TODO: assert something better
        $this->assertContains(
            '<html',
            $gateway->getTicketHtml('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4')
        );
    }

    public function getBoletoSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","pin":"670071563","merchant_payment_code":"248b2672f000e293268be28d6048d600","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-16 19:42:05","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2018-11-22","instalments":"1","payment_type_code":"boleto","boleto_url":"https:\/\/sandbox.ebanx.com\/print\/?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","boleto_barcode":"34191760071244348372714245740007871600000004881","boleto_barcode_raw":"34198716000000048811760012443483721424574000","pre_approved":false,"capture_available":null,"customer":{"document":"40701766000118","email":"sdasneves@r7.com","name":"SR GUSTAVO FERNANDO VALENCIA","birth_date":"1978-03-28"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return BoletoForTests
     */
    private function getTestGateway(Client $client = null)
    {
        $gateway = new Boleto($this->config, $client);
        return $gateway;
    }
}
