<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashPaymentAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class Baloto extends DirectGateway
{
    use Printable;

    protected static function getEnabledCountries()
    {
        return array(Country::COLOMBIA);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::COP,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "baloto";

        $adapter = new CashPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return "https://%s.ebanx.com/print/baloto/?hash=%s";
    }
}
