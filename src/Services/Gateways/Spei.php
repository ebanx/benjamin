<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\EftRequestAdapter;

class Spei extends BaseGateway
{
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
        $payment->type = 'spei';

        $adapter = new EftRequestAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
