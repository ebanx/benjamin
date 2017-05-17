<?php
namespace Tests\Unit\Services\Gateways;

use Tests\TestCase;
use Dotenv\Dotenv;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use Ebanx\Benjamin\Models\Configs\Config;

class TestGateway extends TestCase
{
    protected $config;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->loadEnv();

        $this->config = new Config([
            'sandboxIntegrationKey' => $this->envOrDefault('SANDBOX_INTEGRATION_KEY', 'default_integration_key'),
            'publicSandboxIntegrationKey' => $this->envOrDefault('SANDBOX_PRIVATE_INTEGRATION_KEY', 'default_public_integration_key')
        ]);
    }

    protected function envOrDefault($key, $default)
    {
        return getenv($key) ?: $default;
    }

    protected function loadEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/../../../../');
        $dotenv->load();
    }

    protected function getMockedClient($jsonList)
    {
        if (!is_array($jsonList)) {
            $jsonList = array($jsonList);
        }
        $client = new Client();

        $responses = array();
        foreach ($jsonList as $json) {
            $responses[] = new Response(200, [], Stream::factory($json));
        }

        $mock = new Mock($responses);
        $client->getEmitter()->attach($mock);

        return $client;
    }
}
