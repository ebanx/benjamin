<?php
namespace Ebanx\Benjamin\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\QueryAdapter;
use Ebanx\Benjamin\Services\Http\Client;

class Query
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = $this->client ?: new Client();
    }

    /**
     * @param string $hash
     * @param boolean   $isSandbox
     * @return array
     */
    public function getPaymentInfoByHash($hash, $isSandbox = null)
    {
        $config = clone $this->config;
        if ($isSandbox !== null) {
            $config->isSandbox = $isSandbox;
        }
        $adapter = new QueryAdapter(
            'hash',
            $hash,
            $config
        );

        $response = $this->client->paymentInfo($adapter->transform());
        //TODO: decorate response
        return $response;
    }
}
