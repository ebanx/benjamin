<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Adapters\RequestAdapter;

class Multicaja extends BaseGateway
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
        $payment->type = "multicaja";

        $adapter = new RequestAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
