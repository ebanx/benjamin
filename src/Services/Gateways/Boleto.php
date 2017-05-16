<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\BoletoRequestAdapter;

class Boleto
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create(Payment $payment)
    {
        // TODO: Call payload tranformation service
        // TODO: Call communication service
        // TODO: Return something useful
        $adapter = new BoletoRequestAdapter($payment, $this->config);
        $request = $adapter->transform();
        //var_dump($request);
        return 'hash de pagamento';
    }
}
