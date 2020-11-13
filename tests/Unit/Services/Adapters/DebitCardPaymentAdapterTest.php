<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Services\Adapters\DebitCardPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class DebitCardPaymentAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->debitCard()->businessPerson()->build();

        $adapter = new DebitCardPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'brazilPaymentSchema', 'cardPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }


    public function testAdaptEmptyCard()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->emptydebitCard()->businessPerson()->build();

        $adapter = new DebitCardPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('payment', $result);
    }

    public function testWithManualReview()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->debitCard()->businessPerson()->manualReview(true)->build();

        $adapter = new DebitCardPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('payment', $result);
        $this->assertObjectHasAttribute('manual_review', $result->payment);
        $this->assertEquals(true, $result->payment->manual_review);
    }

    public function testWithoutManualReview()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->debitCard()->businessPerson()->build();

        $adapter = new DebitCardPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('payment', $result);
        $this->assertObjectHasAttribute('manual_review', $result->payment);
        $this->assertEquals(null, $result->payment->manual_review);
    }

    public function testRequestAttributeNumber()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->emptyDebitCard()->businessPerson()->build();

        $adapter = new DebitCardPaymentAdapter($payment, $config);
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