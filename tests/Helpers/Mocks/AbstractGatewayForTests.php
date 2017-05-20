<?php
namespace Tests\Helpers\Mocks;

use Ebanx\Benjamin\Services\Gateways\AbstractGateway;
use GuzzleHttp\Client;

abstract class AbstractGatewayForTests extends AbstractGateway
{
    public static function setClient(Client $client)
    {
        static::$client = $client;
    }
}