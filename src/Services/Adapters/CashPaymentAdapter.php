<?php
namespace Ebanx\Benjamin\Services\Adapters;

class CashPaymentAdapter extends PaymentAdapter
{
    public function transform()
    {
        $transformed = parent::transform();
        $transformed->bypass_boleto_screen = true;

        return $transformed;
    }
}
