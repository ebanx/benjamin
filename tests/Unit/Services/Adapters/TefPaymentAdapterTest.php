<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\TefPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class TefPaymentAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->tef()->businessPerson()->build();

        $adapter = new TefPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'brazilPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}
