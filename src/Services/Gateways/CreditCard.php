<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CardRequestAdapter;
use GuzzleHttp\Client;

class CreditCard extends AbstractGateway
{
    public function create(Payment $payment, Client $client = null)
    {
        $adapter = new CardRequestAdapter($payment, $this->config);
        $request = $adapter->transform();

        $body = $this->requestPayment($request, $client);

        return $body;
    }
}
