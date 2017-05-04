<?php
namespace Ebanx\Benjamin;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Facades;

class Main
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function gateways()
    {
        return new Facades\Gateways($this->config);
    }
}
