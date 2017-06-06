<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;

class Baloto extends BaseGateway
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
        $payment->type = "baloto";

        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->client->payment($request);

        return $body;
    }

    /**
     * @param string $hash
     * @param boolean   $isSandbox
     * @return string
     */
    public function getUrl($hash, $isSandbox = null)
    {
        if ($isSandbox === null) {
            $isSandbox =  $this->config->isSandbox;
        }

        $domain = 'print';
        if ($isSandbox) {
            $domain = 'sandbox';
        }
        return "https://$domain.ebanx.com/print/baloto/?hash=$hash";
    }
}
