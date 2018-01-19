<?php
namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Services\Gateways\BaseGateway;
use Tests\TestCase;
use Tests\Helpers\Environment;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;

class GatewayTestCase extends TestCase
{
    protected $config;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $env = new Environment();

        $this->config = new Config([
            'sandboxIntegrationKey' => $env->read('SANDBOX_INTEGRATION_KEY', 'default_integration_key'),
        ]);
    }

    protected function getExchangeRateSuccessfulResponseJsonWithRate($rate)
    {
        return '{"currency_rate":{"code":"USD","base_code":"???","name":"US Dollar to Something","rate":"'.$rate.'"},"status":"SUCCESS"}';
    }

    protected function assertAvailableForCountries(BaseGateway $gateway, $countries)
    {
        $allCountries = Country::all();

        foreach ($allCountries as $country) {
            if (in_array($country, $countries)) {
                $this->assertTrue($gateway->isAvailableForCountry($country));
                continue;
            }

            $this->assertFalse($gateway->isAvailableForCountry($country));
        }
    }

    protected function assertNotAvailableAnywhere(BaseGateway $gateway)
    {
        $this->assertFalse($gateway->isAvailableForCountry(Country::BRAZIL));
        $this->assertFalse($gateway->isAvailableForCountry(Country::CHILE));
        $this->assertFalse($gateway->isAvailableForCountry(Country::MEXICO));
        $this->assertFalse($gateway->isAvailableForCountry(Country::PERU));
        $this->assertFalse($gateway->isAvailableForCountry(Country::COLOMBIA));
    }
}
