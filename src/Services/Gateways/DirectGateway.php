<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;

abstract class DirectGateway extends BaseGateway
{
    abstract protected function getPaymentData(Payment $payment);

    /**
     * @param  $payment Payment
     * @return array
     */
    public function create(Payment $payment)
    {
        $body = $this->client->payment($this->getPaymentData($payment));

        return $body;
    }

    /**
     * @deprecated 1.3.0 Payment requests should be made using Hosted gateway's create method
     * @param  $payment Payment
     * @return array
     */
    public function request(Payment $payment)
    {
        $body = $this->client->request($this->getPaymentData($payment));

        return $body;
    }
}
