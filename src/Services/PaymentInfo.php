<?php
namespace Ebanx\Benjamin\Services;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\PaymentInfoAdapter;
use Ebanx\Benjamin\Services\Http\Client;

class PaymentInfo
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
     * @return array
     */
    public function findByHash($hash)
    {
        return $this->fetchInfoByType('hash', $hash);
    }

    /**
     * @param string $merchantPaymentCode
     * @return array
     */
    public function findByMerchantPaymentCode($merchantPaymentCode)
    {
        return $this->fetchInfoByType('merchant_payment_code', $merchantPaymentCode);
    }

    /**
     * @param string $type Search type
     * @param string $query Search key
     * @return array
     */
    private function fetchInfoByType($type, $query)
    {
        $adapter = new PaymentInfoAdapter($type, $query, $this->config);
        $response = $this->client->paymentInfo($adapter->transform());
        //TODO: decorate response
        return $response;
    }
}
