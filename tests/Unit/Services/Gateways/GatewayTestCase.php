<?php
namespace Tests\Unit\Services\Gateways;

use Tests\TestCase;
use Tests\Helpers\Environment;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
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
