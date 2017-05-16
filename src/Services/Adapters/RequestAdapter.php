<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;

abstract class RequestAdapter
{
    protected $payment;
    private $config;
    private $countryCode = array(
        'brasil' => 'br',
        'peru' => 'pe',
        'mexico' => 'mx',
        'colombia' => 'co',
        'chile' => 'cl'
    );

    public function __construct(Payment $payment, Config $config)
    {
        $this->payment = $payment;
        $this->config = $config;
    }

    protected function getIntegrationKey()
    {
        return $this->config->isSandbox ? $this->config->sandboxIntegrationKey : $this->config->integrationKey;
    }

    public function transform()
    {
        return (object) array(
            'integration_key' => $this->getIntegrationKey(),
            'operation' => 'request',
            'mode' => 'full',
            'payment' => $this->transformPayment()
        );
    }

    protected function transformPayment()
    {
        return (object) array(
            'name' => $this->payment->person->name,
            'email' => $this->payment->person->email,
            'currency_code' => $this->payment->currencyCode,
            'amount_total' => $this->payment->amountTotal,
            'merchant_payment_code' => $this->payment->merchantPaymentCode,
            'birth_date' => $this->payment->person->birthdate->format('d/m/Y'),
            'customer_ip' => $this->payment->person->ip,
            'zipcode' => $this->payment->address->zipcode,
            'address' => $this->payment->address->address,
            'street_number' => $this->payment->address->streetNumber,
            'street_complement' => $this->payment->address->streetComplement,
            'city' => $this->payment->address->city,
            'state' => $this->payment->address->state,
            'country' => $this->countryCode[strtolower($this->payment->address->country)],
            'phone_number' => $this->payment->person->phoneNumber,
            // TODO: User Value 1-5
        );
    }
}
