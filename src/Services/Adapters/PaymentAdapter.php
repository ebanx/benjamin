<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Payment;

class PaymentAdapter extends BaseAdapter
{
    /**
     * @var Payment
     */
    protected $payment;

    public function __construct(Payment $payment, Config $config)
    {
        $this->payment = $payment;
        parent::__construct($config);
    }

    public function transform()
    {
        return (object) [
            'integration_key' => $this->getIntegrationKey(),
            'operation' => 'request',
            'mode' => 'full',
            'payment' => $this->transformPayment(),
            'currency_code' => $this->config->baseCurrency,
            'name' => $this->payment->person->name,
            'email' => $this->payment->person->email,
            'amount' => $this->payment->amountTotal,
            'merchant_payment_code' => $this->payment->merchantPaymentCode,
            'payment_type_code' => $this->payment->type,
        ];
    }

    protected function transformPayment()
    {
        $userValues = array_replace(
            $this->payment->userValues,
            $this->config->userValues
        );

        $payload = [
            'currency_code' => $this->config->baseCurrency,
            'notification_url' => $this->getNotificationUrl(),
            'redirect_url' => $this->config->redirectUrl,
            'name' => $this->payment->person->name,
            'email' => $this->payment->person->email,
            'amount_total' => $this->payment->amountTotal,
            'merchant_payment_code' => $this->payment->merchantPaymentCode,
            'order_number' => $this->payment->orderNumber,
            'customer_ip' => $this->payment->person->ip,
            'zipcode' => $this->payment->address->zipcode,
            'address' => $this->payment->address->address,
            'street_number' => $this->payment->address->streetNumber,
            'street_complement' => $this->payment->address->streetComplement,
            'city' => $this->payment->address->city,
            'state' => $this->payment->address->state,
            'country' => Country::toIso($this->payment->address->country),
            'phone_number' => $this->payment->person->phoneNumber,
            'note' => $this->payment->note,
            'items' => $this->payment->items,
            'device_id' => $this->payment->deviceId,
            'payment_type_code' => $this->payment->type,
            'user_value_5' => 'Benjamin',
        ];
        if ($birthdate = $this->payment->person->birthdate) {
            $payload['birth_date'] = $birthdate->format('d/m/Y');
        }

        for ($i = 1; $i <= 4; $i++) {
            if (!isset($userValues[$i])) {
                continue;
            }

            $payload['user_value_' . $i] = $userValues[$i];
        }

        return (object) $payload;
    }
}
