<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Services\Adapters\FlowRequestAdapter;

abstract class Flow extends BaseGateway
{
    abstract protected function getFlowMethod();

    protected static function getEnabledCountries()
    {
        return array(Country::CHILE);
    }
    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::CLP,
            Currency::USD,
            Currency::EUR
        );
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = "flowcl";
        $payment->flow_payment_method = $this->getFlowMethod();

        $adapter = new FlowRequestAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
