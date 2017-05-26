<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Tef;
use Ebanx\Benjamin\Services\Http\Client;

class TefTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $tefSuccessfulResponse = $this->getTefSuccessfulResponseJson();
        $client = $this->getMockedClient($tefSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->tef()->businessPerson()->build();
        $gateway = new TefForTests($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Tef($this->config);

        $this->assertAvailableForCountries($gateway, array(
            Country::BRAZIL
        ));
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Tef(new Config(array(
            'baseCurrency' => Currency::BRL
        )));

        $this->assertAvailableForCountries($gateway, array(
            Country::BRAZIL
        ));
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Tef(new Config(array(
            'baseCurrency' => Currency::MXN
        )));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getTefSuccessfulResponseJson()
    {
        return '';
    }
}

class TefForTests extends Tef
{
    public function __construct(Config $config, Client $client)
    {
        parent::__construct($config);
        $this->client = $client;
    }
}
