<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\SafetyPayPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class SafetyPayPaymentAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $adapter = new SafetyPayPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema']));

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

        $adapter = new SafetyPayPaymentAdapter($payment, $config);
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

        $adapter = new SafetyPayPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('safetypay-online', strtolower($result->payment->payment_type_code));
    }

    public function testOnEcuador()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_EC');
        $payment = $factory->payment()->build();
        $payment->type = 'SafetyPayOnline';

        $adapter = new SafetyPayPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals('ec', strtolower($result->payment->country));
    }

    public function testRequestAttributeNumber()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();
        $payment->type = 'SafetyPayCash';

        $adapter = new SafetyPayPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $numberOfKeys = count((array) $result);
        $this->assertEquals(5, $numberOfKeys);
        $this->assertObjectHasAttribute('integration_key', $result);
        $this->assertObjectHasAttribute('operation', $result);
        $this->assertObjectHasAttribute('mode', $result);
        $this->assertObjectHasAttribute('metadata', $result);
        $this->assertObjectHasAttribute('payment', $result);
    }
}
