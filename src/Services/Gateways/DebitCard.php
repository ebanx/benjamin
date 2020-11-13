<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\DebitCardPaymentAdapter;

class DebitCard extends DirectGateway
{
    const API_TYPE = 'debitcard';

    protected static function getEnabledCountries()
    {
        return [
            Country::MEXICO,
            Country::URUGUAY,
        ];
    }
    protected static function getEnabledCurrencies()
    {
        return [
            Currency::MXN,
            Currency::USD,
            Currency::EUR,
            Currency::UYU,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->debit_card->type = self::API_TYPE;

        $adapter = new DebitCardPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
