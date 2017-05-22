<?php
namespace Ebanx\Benjamin\Facades;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Gateways as Services;

class Gateways
{
    public static function boleto(Config $config)
    {
        return new Services\Boleto($config);
    }

    public static function creditCard(Config $config)
    {
        return new Services\CreditCard($config);
    }
}
