<?php
namespace Ebanx\Benjamin\Facades;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Services\Gateways as Services;

class Gateways
{
    public static function boleto($configs=array())
    {
        return new Services\Boleto($configs['Config']);
    }

    public static function creditCard($configs=array())
    {
        return new Services\CreditCard($configs['Config'], $configs['CreditCardConfig']);
    }
}
