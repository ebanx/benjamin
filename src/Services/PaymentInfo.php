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
     * @param boolean   $isSandbox
     * @return array
     */
    public function findByHash($hash, $isSandbox = null)
    {
        return $this->getResponse('hash', $hash, $isSandbox);
    }

    /**
     * @param string $merchantPaymentCode
     * @param boolean   $isSandbox
     * @return array
     */
    public function findByMerchantPaymentCode($merchantPaymentCode, $isSandbox = null)
    {
        return $this->getResponse('merchant_payment_code', $merchantPaymentCode, $isSandbox);
    }

    /**
     * @param $isSandbox
     * @return Config
     */
    private function generateConfig($isSandbox)
    {
        $config = clone $this->config;
        if ($isSandbox !== null) {
            $config->isSandbox = $isSandbox;
        }
        return $config;
    }

    /**
     * @param string $type
     * @param $merchantPaymentCode
     * @param $config
     * @return PaymentInfoAdapter
     */
    private function getAdapter($type, $merchantPaymentCode, $config)
    {
        $adapter = new PaymentInfoAdapter(
            $type,
            $merchantPaymentCode,
            $config
        );
        return $adapter;
    }

    /**
     * @param string $type
     * @param $merchantPaymentCode
     * @param $isSandbox
     * @return array
     */
    private function getResponse($type, $merchantPaymentCode, $isSandbox)
    {
        $config = $this->generateConfig($isSandbox);
        $adapter = $this->getAdapter($type, $merchantPaymentCode, $config);

        $response = $this->client->paymentInfo($adapter->transform());
        //TODO: decorate response
        return $response;
    }
}
