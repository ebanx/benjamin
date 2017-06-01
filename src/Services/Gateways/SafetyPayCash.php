<?php
namespace Ebanx\Benjamin\Services\Gateways;

class SafetyPayCash extends SafetyPay
{
    protected function getPaymentType()
    {
        return 'SafetyPayCash';
    }
}
