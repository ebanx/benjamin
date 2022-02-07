<?php
namespace Ebanx\Benjamin\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\Wallet\MACHPayPaymentAdapter;
use Ebanx\Benjamin\Services\Gateways\DirectGateway;

class MACHPay extends DirectGateway
{
    const API_TYPE = 'wallet';

    protected static function getEnabledCountries()
    {
        return [Country::CHILE];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::CLP,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new MACHPayPaymentAdapter($payment, $this->config);

        return $adapter->transform();
    }
}
