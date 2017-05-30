<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\EftRequestAdapter;

class Eft extends BaseGateway
{
    protected function getEnabledCountries()
    {
        return array(Country::COLOMBIA);
    }

    protected function getEnabledCurrencies()
    {
        return array(
            Currency::COP,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = "eft";

        $adapter = new EftRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->post($request);

        return $body;
    }
}
