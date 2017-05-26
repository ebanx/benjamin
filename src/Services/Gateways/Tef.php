<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\TefRequestAdapter;

class Tef extends BaseGateway
{
    protected function getEnabledCountries()
    {
        return array(Country::BRAZIL);
    }
    protected function getEnabledCurrencies()
    {
        return array(
            Currency::BRL,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = "tef";

        $adapter = new TefRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->post($request);

        return $body;
    }
}
