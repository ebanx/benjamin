<?php
namespace Ebanx\Benjamin\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\Wallet\PicpayPaymentAdapter;
use Ebanx\Benjamin\Services\Gateways\DirectGateway;

class Picpay extends DirectGateway
{
    const API_TYPE = 'wallet';

    protected static function getEnabledCountries()
    {
        return [Country::BRAZIL];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::BRL,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new PicpayPaymentAdapter($payment, $this->config);

        return $adapter->transform();
    }
}
