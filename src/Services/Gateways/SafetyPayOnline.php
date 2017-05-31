<?php
namespace Ebanx\Benjamin\Services\Gateways;

class SafetyPayOnline extends SafetyPay
{
    protected function getPaymentType()
    {
        return 'SafetyPayOnline';
    }
}
