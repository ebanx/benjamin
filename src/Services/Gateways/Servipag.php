<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\EftRequestAdapter;

class Servipag extends BaseGateway
{
    protected static function getEnabledCountries()
    {
        return array(Country::CHILE);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::CLP,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "servipag";

        $adapter = new EftRequestAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
