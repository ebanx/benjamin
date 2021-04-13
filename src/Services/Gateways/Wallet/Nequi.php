<?php
namespace Ebanx\Benjamin\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\Wallet\NequiPaymentAdapter;
use Ebanx\Benjamin\Services\Gateways\DirectGateway;

class Nequi extends DirectGateway
{
    const API_TYPE = 'wallet';

    protected static function getEnabledCountries()
    {
        return [Country::COLOMBIA];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::COP,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new NequiPaymentAdapter($payment, $this->config);

        return $adapter->transform();
    }
}
