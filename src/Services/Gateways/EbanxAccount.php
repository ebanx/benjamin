<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Bank;
use Ebanx\Benjamin\Models\Payment;

class EbanxAccount extends Tef
{
    public function create(Payment $payment)
    {
        $payment->bankCode = Bank::EBANX_ACCOUNT;

        return parent::create($payment);
    }
}
