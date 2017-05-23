<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;
use Tests\Helpers\Mocks\AbstractGatewayForTests;

class OxxoTest extends TestGateway
{
    public function testPayment()
    {
        $oxxoSuccessfulResponse = $this->getOxxoSuccessfulResponseJson();
        $client = $this->getMockedClient($oxxoSuccessfulResponse);

        $payment = BuilderFactory::payment()->oxxo()->businessPerson()->build();
        AbstractGatewayForTests::setClient($client);
        $result = EBANX($this->config)->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function getOxxoSuccessfulResponseJson()
    {
        return 'oloco';
    }
}
