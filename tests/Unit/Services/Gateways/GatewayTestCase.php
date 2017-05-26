<?php
namespace Tests\Unit\Services\Gateways;

use Tests\TestCase;
use Tests\Helpers\Environment;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;

class GatewayTestCase extends TestCase
{
    protected $config;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $env = new Environment();

        $this->config = new Config([
            'sandboxIntegrationKey' => $env->read('SANDBOX_INTEGRATION_KEY', 'default_integration_key')
        ]);
    }

    protected function assertAvailableForCountries($gateway, $countries)
    {
        $allCountries = array(
            Country::BRAZIL,
            Country::CHILE,
            Country::MEXICO,
            Country::PERU,
            Country::COLOMBIA
        );

        foreach ($allCountries as $country) {
            if (in_array($country, $countries)) {
                $this->assertTrue($gateway->isAvailableForCountry($country));
                continue;
            }

            $this->assertFalse($gateway->isAvailableForCountry($country));
        }
    }
    protected function assertNotAvailableAnywhere($gateway)
    {
        $this->assertFalse($gateway->isAvailableForCountry(Country::BRAZIL));
        $this->assertFalse($gateway->isAvailableForCountry(Country::CHILE));
        $this->assertFalse($gateway->isAvailableForCountry(Country::MEXICO));
        $this->assertFalse($gateway->isAvailableForCountry(Country::PERU));
        $this->assertFalse($gateway->isAvailableForCountry(Country::COLOMBIA));
    }

    protected function getMockedClient($response)
    {
        return new ClientForTests(new EchoEngine($response));
    }
}
