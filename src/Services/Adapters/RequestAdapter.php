<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;

abstract class RequestAdapter
{
    private $payment;
    private $config;

    public function __construct(Payment $payment, Config $config)
    {
        $this->payment = $payment;
        $this->config = $config;
    }

    protected function getIntegrationKey()
    {
        return $this->config->isSandbox ? $this->config->sandboxIntegrationKey : $this->config->integrationKey;
    }

    public function transform()
    {
        return (object) array(
            'integration_key' => $this->getIntegrationKey(),
            'operation' => 'request',
            'mode' => 'full',
            'person_type' => $this->payment->person->type,
            'payment' => $this->transformPayment()
        );
    }

    protected function transformPayment()
    {
        return (object) array(

        );
    }
}
