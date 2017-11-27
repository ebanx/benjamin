<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Gateways\Hosted;

class HostedTest extends GatewayTestCase
{
    public function testPayment()
    {
        $hostedSuccessfulResponse = $this->getHostedSuccessfulResponseJson();
        $client = $this->getMockedClient($hostedSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $request = $factory->request()->build();
        $gateway = new Hosted($this->config, $client);

        $result = $gateway->create($request);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $gateway = new Hosted($this->config);

        $this->assertAvailableForCountries($gateway, Country::all());
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new Hosted(new Config([
            'baseCurrency' => Currency::ARS,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::ARGENTINA,
        ]);
    }

    public function getHostedSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"'.md5(rand(1, 999)).'"},"status":"SUCCESS"}';
    }
}
