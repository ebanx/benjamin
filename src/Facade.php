<?php
namespace Ebanx\Benjamin;

use Ebanx\Benjamin\Facades;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Configs\AddableConfig;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Gateways;

class Facade
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
     * @return Facade
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
     * @return Facade
     */
    public function withConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param CreditCardConfig $creditCardConfig
     * @return Facade
     */
    public function withCreditCardConfig(CreditCardConfig $creditCardConfig)
    {
        $this->creditCardConfig = $creditCardConfig;
        return $this;
    }

    /**
     * @param Payment $payment
     * @return array
     * @throws \InvalidArgumentException
     */
    public function create(Payment $payment)
    {
        if ($payment->type === null) {
            throw new \InvalidArgumentException('Invalid payment type');
        }
        if (!method_exists($this, $payment->type)) {
            throw new \InvalidArgumentException('Invalid payment type');
        }

        $instance = call_user_func(array($this, $payment->type));
        return $instance->create($payment);
    }

    # Gateways

    /**
     * @return Gateways\Baloto
     */
    public function baloto()
    {
        return new Gateways\Baloto($this->config);
    }

    /**
     * @return Gateways\Boleto
     */
    public function boleto()
    {
        return new Gateways\Boleto($this->config);
    }

    /**
     * @param  CreditCardConfig $creditCardConfig (optional) credit card config
     * @return Gateways\CreditCard
     */
    public function creditCard(CreditCardConfig $creditCardConfig = null)
    {
        if ($creditCardConfig === null) {
            $creditCardConfig = $this->creditCardConfig;
        }

        return new Gateways\CreditCard($this->config, $creditCardConfig);
    }

    /**
     * @return Gateways\Oxxo
     */
    public function oxxo()
    {
        return new Gateways\Oxxo($this->config);
    }

    /**
     * @return Gateways\Sencillito
     */
    public function sencillito()
    {
        return new Gateways\Sencillito($this->config);
    }

    /**
     * @return Gateways\PagoEfectivo
     */
    public function pagoEfectivo()
    {
        return new Gateways\PagoEfectivo($this->config);
    }

    /**
     * @return Gateways\Tef
     */
    public function tef()
    {
        return new Gateways\Tef($this->config);
    }

    /**
     * @return Gateways\EbanxAccount
     */
    public function ebanxAccount()
    {
        return new Gateways\EbanxAccount($this->config);
    }

    /**
     * @return Gateways\Eft
     */
    public function eft()
    {
        return new Gateways\Eft($this->config);
    }

    /**
     * @return Gateways\Servipag
     */
    public function servipag()
    {
        return new Gateways\Servipag($this->config);
    }
}
