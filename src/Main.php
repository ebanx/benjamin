<?php
namespace Ebanx\Benjamin;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Facades;
use Ebanx\Benjamin\Models\Payment;
use Psr\Log\InvalidArgumentException;

class Main
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create(Payment $payment)
    {
        if (!method_exists('Ebanx\Benjamin\Facades\Gateways', $payment->type)) {
            throw new InvalidArgumentException('Invalid payment type');
        }
        $instance = call_user_func(array('Ebanx\Benjamin\Facades\Gateways', $payment->type), $this->config);
        return $instance->create($payment);
    }

    public function create(Payment $payment)
    {
        if (!method_exists('Ebanx\Benjamin\Facades\Gateways', $payment->type)) {
            throw new InvalidArgumentException('Invalid payment type');
        }
        $instance = call_user_func(array('Ebanx\Benjamin\Facades\Gateways', $payment->type), $this->config);
        return $instance->create($payment);
    }
}
