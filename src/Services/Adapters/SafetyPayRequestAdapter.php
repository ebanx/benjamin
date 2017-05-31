<?php
namespace Ebanx\Benjamin\Services\Adapters;

class SafetyPayRequestAdapter extends RequestAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = substr_replace($this->payment->type, '-', 9, 0);

        return $transformed;
    }
}
