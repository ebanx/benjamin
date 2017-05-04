<?php
namespace Ebanx\Benjamin\Facades;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Gateways as Services;

class Gateways
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function boleto()
    {
        return new Services\Boleto($this->config);
    }
}
