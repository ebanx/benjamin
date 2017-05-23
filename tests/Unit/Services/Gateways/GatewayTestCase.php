<?php
namespace Tests\Unit\Services\Gateways;

use Tests\TestCase;
use Tests\Helpers\Environment;
use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Ebanx\Benjamin\Models\Configs\Config;

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

    protected function getMockedClient($response)
    {
        return new ClientForTests(new EchoEngine($response));
    }
}
