<?php
namespace Ebanx\Benjamin\Services\Adapters;

class WebpayRequestAdapter extends RequestAdapter
{
    protected function transformPayment()
    {
        $transformed = parent::transformPayment();
        $transformed->flow_payment_type = 'webpay';

        return $transformed;
    }
}
