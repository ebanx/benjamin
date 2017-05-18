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
    abstract public function create(Payment $payment, Client $client = null);

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function getUrl()
    {
        return $this->config->isSandbox ? 'https://sandbox.ebanx.com/ws/direct' : 'https://api.ebanx.com/ws/direct';
    }

    protected function requestPayment(\stdClass $request , Client $client = null)
    {
        // TODO: Maybe some Dependency Injection?
        $client = $client ?: new Client();

        $response = $client->post($this->getUrl(), array(
            'json' => $request
        ));

        return $response->json();
    }
}
