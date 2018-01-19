<?php
namespace Tests;

use Tests\Helpers\Mocks\Http\ClientForTests;
use Tests\Helpers\Mocks\Http\EchoEngine;
use Ebanx\Benjamin\Services\Http\Client;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getMockedClient($response)
    {
        return new ClientForTests(new EchoEngine(Client::SANDBOX_URL, $response));
    }
}
