<?php
namespace Ebanx\Benjamin\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\RefundAdapter;
use Ebanx\Benjamin\Services\Http\Client;

class Refund
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
     * @param string    $hash           The payment hash.
     * @param float     $amount         The amount to be refunded; expressed in the original payment currency.
     * @param string    $description    Description of the refund reason.
     * @return array
     */
    public function requestByHash($hash, $amount, $description)
    {
        $data = array(
            'hash' => $hash,
            'amount' => $amount,
            'description' => $description
        );

        $adapter = new RefundAdapter($data, $this->config);
        $response = $this->client->refund($adapter->transform());

        return $response;
    }
}
