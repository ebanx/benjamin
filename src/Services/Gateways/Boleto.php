<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\BoletoRequestAdapter;
use GuzzleHttp\Client;

class Boleto
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create(Payment $payment)
    {
        // TODO: Call communication service
        // TODO: Return something useful
        $adapter = new BoletoRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $client = new Client();

        $url = 'https://api.ebanx.com/ws/direct';
        if ($this->config->isSandbox) {
            $url = 'https://sandbox.ebanx.com/ws/direct';
        }

        $response = $client->post($url, array(
            'json' => $request
        ));

        $body = $response->json();

        return $body;
    }
}
