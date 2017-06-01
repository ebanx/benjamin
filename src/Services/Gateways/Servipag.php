<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\EftRequestAdapter;

class Servipag extends BaseGateway
{
    protected function getEnabledCountries()
    {
        return array(Country::CHILE);
    }

    protected function getEnabledCurrencies()
    {
        return array(
            Currency::CLP,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = "servipag";

        $adapter = new EftRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }
}
