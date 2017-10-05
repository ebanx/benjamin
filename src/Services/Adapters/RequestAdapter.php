<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Payment;

class RequestAdapter extends BaseAdapter
{
    /**
     * @var Payment
     */
    protected $payment;

    private $countryCode = array(
        Country::BRAZIL => 'br',
        Country::PERU => 'pe',
        Country::MEXICO => 'mx',
        Country::COLOMBIA => 'co',
        Country::CHILE => 'cl'
    );

    public function __construct(Payment $payment, Config $config)
    {
        $this->payment = $payment;
        parent::__construct($config);
    }

    public function transform()
    {
        return (object) array(
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
            'country' => $this->countryCode[$this->payment->address->country],
            'phone_number' => $this->payment->person->phoneNumber,
            'note' => $this->payment->note,
            'items' => $this->transformItems(),
            'device_id' => $this->payment->deviceId,
            'payment_type_code' => $this->payment->type,
            'user_value_5' => 'Benjamin'
        );
        if ($birthdate = $this->payment->person->birthdate) {
            $payload['birth_date'] = $birthdate->format('d/m/Y');
        }

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
