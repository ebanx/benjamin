<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;

class Oxxo extends BaseGateway
{
    protected function getEnabledCountries()
    {
        return array(Country::MEXICO);
    }
    protected function getEnabledCurrencies()
    {
        return array(
            Currency::MXN,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $payment->type = "oxxo";

        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }

    /**
     * @param string $hash
     * @return string
     */
    public function getUrl($hash)
    {
        return 'https://sandbox.ebanx.com/print/oxxo/?hash=' . $hash;
    }
}
