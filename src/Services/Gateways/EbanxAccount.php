<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Bank;
use Ebanx\Benjamin\Models\Payment;

class EbanxAccount extends Tef
{
    protected function getPaymentData(Payment $payment)
    {
        $payment->bankCode = Bank::EBANX_ACCOUNT;

        return parent::getPaymentData($payment);
    }
}
