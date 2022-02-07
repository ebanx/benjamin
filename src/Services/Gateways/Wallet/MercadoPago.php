<?php
namespace Ebanx\Benjamin\Services\Gateways\Wallet;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\Wallet\MercadoPagoPaymentAdapter;
use Ebanx\Benjamin\Services\Gateways\DirectGateway;

class MercadoPago extends DirectGateway
{
    const API_TYPE = 'wallet';

    protected static function getEnabledCountries()
    {
        return [
            Country::ARGENTINA,
            Country::BRAZIL,
            Country::MEXICO
        ];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::ARS,
            Currency::BRL,
            Currency::MXN,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new MercadoPagoPaymentAdapter($payment, $this->config);

        return $adapter->transform();
    }
}
