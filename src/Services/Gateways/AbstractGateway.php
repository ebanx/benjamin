<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Http\Client;

abstract class AbstractGateway
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    abstract public function create(Payment $payment);

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();

        if (!$this->config->isSandbox) {
            $this->client->inLiveMode();
        }
    }
}
