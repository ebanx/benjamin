<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class Baloto extends BaseGateway
{
    use Printable;

    protected static function getEnabledCountries()
    {
        return array(Country::COLOMBIA);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::COP,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = "baloto";

        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return "https://%s.ebanx.com/print/baloto/?hash=%s";
    }
}
