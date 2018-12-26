<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Person;
use Ebanx\Benjamin\Services\Adapters\BankTransferPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use Ebanx\Benjamin\Models\Configs\Config;

class BankTransferPaymentAdapterTest extends PaymentAdapterTest
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->banktransfer()->businessPerson()->build();

        $adapter = new BankTransferPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'brazilPaymentSchema', 'cashPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }

    public function testDueDateIsInsidePayment()
    {
        $payment = new Payment([
            'dueDate' => new \DateTime(),
            'person' => new Person(),
            'address' => new Address(),
        ]);

        $adapter = new BankTransferPaymentAdapter($payment, new Config());
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('due_date', $result->payment);
    }

    public function testRequestAttributeNumber()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->banktransfer()->businessPerson()->build();

        $adapter = new BankTransferPaymentAdapter($payment, $config);
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
