<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class Boleto extends BaseGateway
{
    use Printable;

    protected static function getEnabledCountries()
    {
        return array(Country::BRAZIL);
    }

    protected static function getEnabledCurrencies()
    {
        return array(
            Currency::BRL,
            Currency::USD,
            Currency::EUR
        );
    }

    public function create(Payment $payment)
    {
        $body = $this->client->payment($this->getPaymentData($payment));

        return $body;
    }

    public function request(Payment $payment)
    {
        $body = $this->client->request($this->getPaymentData($payment));

        return $body;
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return "https://%s.ebanx.com/print/?hash=%s";
    }

    /**
     * @param Payment $payment
     * @return object
     */
    private function getPaymentData(Payment $payment)
    {
        $payment->type = "boleto";

        $adapter = new CashRequestAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
