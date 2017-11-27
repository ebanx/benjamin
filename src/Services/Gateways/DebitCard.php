<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CardPaymentAdapter;

class DebitCard extends BaseGateway
{
    protected static function getEnabledCountries()
    {
        return [
            Country::MEXICO,
        ];
    }
    protected static function getEnabledCurrencies()
    {
        return [
            Currency::MXN,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "debitcard";
        $payment->card->type = 'debitcard';

        $adapter = new CardPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
