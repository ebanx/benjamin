<?php
namespace Ebanx\Benjamin\Services\Adapters;

class PixPaymentAdapter extends BrazilPaymentAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = 'pix';

        return $transformed;
    }
}
