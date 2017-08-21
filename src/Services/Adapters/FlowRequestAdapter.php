<?php
namespace Ebanx\Benjamin\Services\Adapters;

class FlowRequestAdapter extends RequestAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->flow_payment_method = $this->payment->flow_payment_method;

        return $transformed;
    }
}
