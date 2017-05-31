<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\SafetyPayRequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class SafetyPayRequestAdapterTest extends RequestAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $adapter = new SafetyPayRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['requestSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }

    public function testCashTypeCode()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();
        $payment->type = 'SafetyPayCash';

        $adapter = new SafetyPayRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('safetypay-cash', strtolower($result->payment->payment_type_code));
    }

    public function testOnlineTypeCode()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();
        $payment->type = 'SafetyPayOnline';

        $adapter = new SafetyPayRequestAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('safetypay-online', strtolower($result->payment->payment_type_code));
    }
}
