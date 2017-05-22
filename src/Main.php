<?php
namespace Ebanx\Benjamin;

use Ebanx\Benjamin\Facades;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Configs\AddableConfig;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Gateways\AbstractGateway;
use Psr\Log\InvalidArgumentException;

class Main
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CreditCardConfig
     */
    private $creditCardConfig;

    /**
     * @param AddableConfig $config,... Configuration objects
     * @return Main
     */
    public function addConfig(AddableConfig $config)
    {
        $args = func_get_args();
        foreach ($args as $config) {
            $class = $config->getShortClassName();
            call_user_func(array($this, 'with'.$class), $config);
        }

        return $this;
    }

    /**
     * @param Config $config
     * @return Main
     */
    public function withConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param CreditCardConfig $creditCardConfig
     * @return Main
     */
    public function withCreditCardConfig(CreditCardConfig $creditCardConfig)
    {
        $this->creditCardConfig = $creditCardConfig;
        return $this;
    }

    /**
     * @param Payment $payment
     * @return array
     * @throws InvalidArgumentException
     */
    public function create(Payment $payment)
    {
        if ($payment->type === null) {
            throw new InvalidArgumentException('Invalid payment type');
        }

        $instance = call_user_func(array($this, $payment->type));
        return $instance->create($payment);
    }

    /**
     * @param  string $gateway Gateway name
     * @param  array  $args
     * @return AbstractGateway
     */
    public function __call($gateway, $args = array())
    {
        if (!method_exists('Ebanx\Benjamin\Facades\Gateways', $gateway)) {
            throw new InvalidArgumentException('Invalid payment type');
        }

        $arguments = array(
            'Config' => $this->config,
            'CreditCardConfig' => $this->creditCardConfig
        );

        if (count($args) > 0) {
            foreach ($args as $config) {
                $key = $config->getShortClassName();
                $arguments[$key] = $config;
            }
        }

        $instance = call_user_func(
            array('Ebanx\Benjamin\Facades\Gateways', $gateway),
            $arguments
        );

        return $instance;
    }
}
