<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\SafetyPayRequestAdapter;

class SafetyPayCash extends BaseGateway
{
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
        $payment->type = "safetyPayCash";

        $adapter = new SafetyPayRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }
}
