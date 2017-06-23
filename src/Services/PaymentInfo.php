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
        $this->switchMode(null);
    }

    /**
     * @param string    $hash
     * @param bool|null $isSandbox
     * @return array
     */
    public function findByHash($hash, $isSandbox = null)
    {
        $this->switchMode($isSandbox);
        try {
            return $this->fetchInfoByType('hash', $hash);
        } finally {
            $this->switchMode(null);
        }
    }

    /**
     * @param string $merchantPaymentCode
     * @param bool|null $isSandbox
     * @return array
     */
    public function findByMerchantPaymentCode($merchantPaymentCode, $isSandbox = null)
    {
        $this->switchMode($isSandbox);
        try {
            return $this->fetchInfoByType('merchant_payment_code', $merchantPaymentCode);
        } finally {
            $this->switchMode(null);
        }
    }

    /**
     * @param  bool|null $toSandbox Switch to default(null) sandbox(true) or live(false) modes
     * @return void
     */
    private function switchMode($toSandbox) {
        if ($toSandbox === null) {
            $toSandbox = $this->config->isSandbox;
        }

        if ($toSandbox) {
            $this->client->inSandboxMode();
            return;
        }
        $this->client->inLiveMode();
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
