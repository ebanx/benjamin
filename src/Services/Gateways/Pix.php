<?php

namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\PixPaymentAdapter;

class Pix extends DirectGateway
{
    const API_TYPE = 'pix';

    protected static function getEnabledCountries()
    {
        return [Country::BRAZIL];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::BRL,
            Currency::USD,
            Currency::EUR,
        ];
    }

    /**
     * @param Payment $payment
     *
     * @return object
     */
    protected function getPaymentData(Payment $payment)
    {
        $adapter = new PixPaymentAdapter($payment, $this->config);

        return $adapter->transform();
    }
}
