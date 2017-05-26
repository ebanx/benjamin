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
        $userValues = array_replace(
            $this->payment->userValues,
            $this->config->userValues
        );

        $payload = array(
            'currency_code' => $this->config->baseCurrency,
            'notification_url' => $this->config->notificationUrl,
            'name' => $this->payment->person->name,
            'email' => $this->payment->person->email,
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
            'note' => $this->payment->note,
            'items' => $this->transformItems(),
            'device_id' => $this->payment->deviceId,
            'payment_type_code' => $this->payment->type,
            'user_value_5' => 'Benjamin'
        );

        for ($i = 1; $i <= 4; $i++) {
            if (!isset($userValues[$i])) {
                continue;
            }

            $payload['user_value_'.$i] = $userValues[$i];
        }

        return (object) $payload;
    }

    protected function transformItems()
    {
        $itemArray = array();

        foreach ($this->payment->items as $item) {
            $itemArray[] = (object) array(
                'name' => $item->name,
                'description' => $item->description,
                'unit_price' => $item->unitPrice,
                'quantity' => $item->quantity,
                'type' => $item->type
            );
        }

        return (object) $itemArray;
    }
}
