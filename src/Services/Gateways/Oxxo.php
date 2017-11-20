<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashPaymentAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class Oxxo extends DirectGateway
{
    use Printable;

    protected static function getEnabledCountries()
    {
        return array(Country::MEXICO);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::MXN,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "oxxo";

        $adapter = new CashPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return "https://%s.ebanx.com/print/oxxo/?hash=%s";
    }
}
