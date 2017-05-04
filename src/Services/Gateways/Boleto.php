<?php
namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Payment;

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
        return 'hash de pagamento';
    }
}
