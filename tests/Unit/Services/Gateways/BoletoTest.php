<?php
namespace Tests\Unit\Services\Gateways;

use Ebanx\Benjamin\Models\Configs\Config;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\TestCase;

class BoletoTest extends TestCase
{
    public function testBusinessPersonPayment()
    {
        $config = new Config();

        $payment = BuilderFactory::payment()->boleto()->businessPerson()->build();
        $result = Benjamin($config)->gateways()->boleto()->create($payment);

        $this->assertEquals('hash de pagamento', $result);

        // TODO: create person, address and payment
        // TODO: call main api and boleto gateway
        // TODO: assert output (to be defined)
    }
}
