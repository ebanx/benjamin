<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Bank;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Currency;

class EbanxAccount extends Tef
{
    protected function getPaymentData(Payment $payment)
    {
        $payment->bankCode = Bank::EBANX_ACCOUNT;

        return parent::getPaymentData($payment);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::BRL,
            Currency::USD
        );
    }
}
