<?php
namespace Ebanx\Benjamin\Services\Adapters;

class EftRequestAdapter extends RequestAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->eft_code = $this->payment->bankCode;

        return $transformed;
    }
}
