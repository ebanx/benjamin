<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class Pagofacil extends BaseGateway
{
    use Printable;

    protected static function getEnabledCountries()
    {
        return array(Country::ARGENTINA);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::ARS,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = 'pagofacil';

        $adapter = new CashRequestAdapter($payment, $this->config);
        return $adapter->transform();
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return "https://%s.ebanx.com/print/voucher/?hash=%s";
    }
}
