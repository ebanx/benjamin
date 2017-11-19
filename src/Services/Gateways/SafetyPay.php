<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\SafetyPayPaymentAdapter;

abstract class SafetyPay extends BaseGateway
{
    abstract protected function getPaymentType();

    protected static function getEnabledCountries()
    {
        return array(Country::PERU);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::PEN,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = $this->getPaymentType();

        $adapter = new SafetyPayPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
