<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\SafetyPayRequestAdapter;

abstract class SafetyPay extends BaseGateway
{
    abstract protected function getPaymentType();

    protected function getEnabledCountries()
    {
        return array(Country::PERU);
    }

    protected function getEnabledCurrencies()
    {
        return array(
            Currency::PEN,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = $this->getPaymentType();

        $adapter = new SafetyPayRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }
}
