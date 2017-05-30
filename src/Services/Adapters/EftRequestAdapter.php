<?php
namespace Ebanx\Benjamin\Services\Adapters;

class EftRequestAdapter extends RequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->eft_code = $this->payment->bankCode;

        return $transformed;
    }
}
