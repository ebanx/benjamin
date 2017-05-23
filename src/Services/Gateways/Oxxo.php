<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;

class Oxxo extends AbstractGateway
{
    public function create(Payment $payment)
    {
        $payment->type = "oxxo";

        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->requestPayment($request);

        return $body;
    }
}
