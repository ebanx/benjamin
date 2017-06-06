<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;

class Sencillito extends BaseGateway
{
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

    public function create(Payment $payment)
    {
        $payment->type = "sencillito";

        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }
}
