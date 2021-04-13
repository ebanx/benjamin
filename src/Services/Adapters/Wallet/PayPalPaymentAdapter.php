<?php
namespace Ebanx\Benjamin\Services\Adapters\Wallet;

use Ebanx\Benjamin\Models\Wallet;
use Ebanx\Benjamin\Services\Adapters\BrazilPaymentAdapter;

class PayPalPaymentAdapter extends BrazilPaymentAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = Wallet::PAYPAL;

        return $transformed;
    }
}
