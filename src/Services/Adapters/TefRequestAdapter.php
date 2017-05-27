<?php
namespace Ebanx\Benjamin\Services\Adapters;

class TefRequestAdapter extends BrazilRequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = $this->payment->bankCode;

        return $transformed;
    }
}
