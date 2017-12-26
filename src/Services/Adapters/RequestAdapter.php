<?php
namespace Ebanx\Benjamin\Services\Adapters;

use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Request;
use Ebanx\Benjamin\Models\Configs\Config;

class RequestAdapter extends BaseAdapter
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request, Config $config)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * @return object
     */
    public function transform()
    {
        $none = '-';

        $result = [
            'integration_key' => $this->getIntegrationKey(),
            'name' => $this->request->name,
            'country' => $this->countryCode[$this->request->country],
            'phone_number' => $none,
            'email' => $this->request->email,
            'currency_code' => $this->config->baseCurrency,
            'amount' => $this->request->amount,
            'merchant_payment_code' => $this->request->merchantPaymentCode,
            'order_number' => $this->request->orderNumber,
            'payment_type_code' => $this->request->type,
            'instalments' => implode('-', [
                $this->request->minInstalments,
                $this->request->maxInstalments,
            ]),
            'notification_url' => $this->transformNotificationUrl(),
        ];

        $result = $this->transformAddress($result, $this->request->address);
        $result = $this->transformUserValues($result);

        return (object) $result;
    }

    protected function transformNotificationUrl()
    {
        if (!isset($this->config->notificationUrl)) {
            return '';
        }

        return $this->config->notificationUrl;
    }

    protected function transformUserValues($result)
    {
        $userValues = array_replace(
            $this->request->userValues,
            $this->config->userValues,
            [5 => 'Benjamin']
        );

        for ($i = 1; $i <= 5; $i++) {
            if (!isset($userValues[$i])) {
                continue;
            }

            $result['user_value_' . $i] = $userValues[$i];
        }

        return $result;
    }

    protected function transformAddress(array $result, Address $address = null)
    {
        if (!$address) {
            return $result;
        }

        return array_replace($result, [
            'zipcode' => $address->zipcode,
            'address' => $address->address,
            'street_number' => $address->streetNumber,
            'street_complement' => $address->streetComplement,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $this->countryCode[$address->country]
        ]);
    }
}
