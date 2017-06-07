<?php
namespace Tests\Unit\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Http\Client;
use Tests\Helpers\Environment;
use Tests\TestCase;
use Ebanx\Benjamin\Services\Query;

class QueryTest extends TestCase
{
    private $config;
    private $client;

    public function setup()
    {
        $env = new Environment();

        $this->config = new Config([
            'sandboxIntegrationKey' => $env->read('SANDBOX_INTEGRATION_KEY', 'default_integration_key')
        ]);
//        $this->client = new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $text));
    }

    public function testSiteToLocalConvertion()
    {
        $query = new Query($this->config);
        $info = $query->getPaymentInfoByHash('hash');
    }
}

class QueryForTests extends Query
{
    public function __construct(Config $config, Client $client)
    {
        $this->client = $client;
        parent::__construct($config);
    }
}
