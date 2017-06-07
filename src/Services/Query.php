<?php
namespace Ebanx\Benjamin\Services;

use Ebanx\Benjamin\Models\Configs\Config;
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
     * @return string
     */
    public function getPaymentInfoByHash($hash)
    {
        //call EBANX API
        //return payment info
        return $hash;
    }
}
