<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashRequestAdapter;
use GuzzleHttp\Client;

class Boleto extends AbstractGateway
{
    public function create(Payment $payment)
    {
        $adapter = new CashRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->requestPayment($request);

        return $body;
    }
}
