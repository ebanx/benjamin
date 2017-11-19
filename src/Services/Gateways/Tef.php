<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\TefPaymentAdapter;

class Tef extends BaseGateway
{
    protected static function getEnabledCountries()
    {
        return array(Country::BRAZIL);
    }
    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::BRL,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "tef";

        $adapter = new TefPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
