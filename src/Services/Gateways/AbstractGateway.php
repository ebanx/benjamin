<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use GuzzleHttp\Client;

abstract class AbstractGateway
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Client
     */
    protected static $client;

    abstract public function create(Payment $payment);

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function getUrl()
    {
        return $this->config->isSandbox ? 'https://sandbox.ebanx.com/ws/direct' : 'https://api.ebanx.com/ws/direct';
    }

    protected function requestPayment(\stdClass $request)
    {
        $client = self::getClient();

        $response = $client->post($this->getUrl(), array(
            'json' => $request
        ));

        return $response->json();
    }

    private static function getClient()
    {
        if (is_null(self::$client)) {
            self::$client = new Client();
        }
        return self::$client;
    }
}
