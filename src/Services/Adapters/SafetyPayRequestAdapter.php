<?php
namespace Ebanx\Benjamin\Services\Adapters;

class SafetyPayRequestAdapter extends RequestAdapter
{
    public function transform()
    {
        $transformed = parent::transform();

        return $transformed;
    }

    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->payment_type_code = 'safetypay-cash';

        return $transformed;
    }
}
