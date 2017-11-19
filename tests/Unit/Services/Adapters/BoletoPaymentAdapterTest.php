<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\BoletoPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class BoletoPaymentAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->boleto()->businessPerson()->build();

        $adapter = new BoletoPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'brazilPaymentSchema', 'cashPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}
