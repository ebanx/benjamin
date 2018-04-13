<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Person;
use Ebanx\Benjamin\Services\Adapters\PaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\TestCase;
use JsonSchema;

class PaymentAdapterTest extends TestCase
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->boleto()->businessPerson()->build();

        $adapter = new FakeAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator();
        $validator->validate($result, $this->getSchema('paymentSchema'));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }

    public function testIntegrationKey()
    {
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $liveKey = 'testIntegrationKey';
        $sandboxKey = 'testSandboxIntegrationKey';

        $config = new Config([
            'integrationKey' => $liveKey,
            'sandboxIntegrationKey' => $sandboxKey
        ]);

        // Sandbox
        $adapter = new FakeAdapter($payment, $config);
        $this->assertEquals($sandboxKey, $adapter->getIntegrationKey());

        // Live
        $config->isSandbox = false;
        $adapter = new FakeAdapter($payment, $config);
        $this->assertEquals($liveKey, $adapter->getIntegrationKey());
    }

    public function testUserValues()
    {
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $expected = [
            1 => 'from_tests',
            2 => 'DO NOT PAY',
            5 => 'Benjamin',
        ];

        $payment->userValues = [
            1 => 'Override me',
            2 => 'DO NOT PAY',
        ];

        $config = new Config([
            'userValues' => [
                1 => 'from_tests',
            ],
        ]);

        $adapter = new FakeAdapter($payment, $config);
        $result = $adapter->transform();

        $resultValues = array_filter([
            1 => isset($result->payment->user_value_1) ? $result->payment->user_value_1 : null,
            2 => isset($result->payment->user_value_2) ? $result->payment->user_value_2 : null,
            3 => isset($result->payment->user_value_3) ? $result->payment->user_value_3 : null,
            4 => isset($result->payment->user_value_4) ? $result->payment->user_value_4 : null,
            5 => isset($result->payment->user_value_5) ? $result->payment->user_value_5 : null,
        ]);

        $this->assertEquals($expected, $resultValues);
    }

    public function testSiteCurrencyCOP()
    {
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $config = new Config([
            'baseCurrency' => Currency::COP
        ]);

        $adapter = new FakeAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals(Currency::COP, $result->payment->currency_code);
    }
    public function testSiteCurrencyEUR()
    {
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->build();

        $config = new Config([
            'baseCurrency' => Currency::EUR
        ]);

        $adapter = new FakeAdapter($payment, $config);
        $result = $adapter->transform();

        $this->assertEquals(Currency::EUR, $result->payment->currency_code);
    }

    public function testTransformDocument()
    {
        $document = '123456789';
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(['document' => $document]),
            'address' => new Address()
        ]), new Config());
        $result = $adapter->transform();

        $this->assertEquals($document, $result->payment->document);
    }

    public function testTransformWithoutDocument()
    {
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(),
            'address' => new Address()
        ]), new Config());
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('document', $result->payment);
    }

    public function testTransformDocumentType()
    {
        $documentType = Person::DOCUMENT_TYPE_ARGENTINA_CDI;
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(['documentType' => $documentType]),
            'address' => new Address()
        ]), new Config());
        $result = $adapter->transform();

        $this->assertEquals($documentType, $result->payment->document_type);
    }

    public function testTransformWithoutDocumentType()
    {
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(),
            'address' => new Address()
        ]), new Config());
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('document_type', $result->payment);
    }

    public function testTransformProfileId()
    {
        $riskProfileId = 'Bx1x0x0';
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(),
            'address' => new Address(),
            'riskProfileId' => $riskProfileId,
        ]), new Config());
        $result = $adapter->transform();

        $this->assertEquals($riskProfileId, $result->metadata->risk->profile_id);
    }

    public function testTransformWithoutProfileId()
    {
        $adapter = new FakeAdapter(new Payment([
            'person' => new Person(),
            'address' => new Address(),
        ]), new Config());
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('profile_id', $result->metadata->risk);
    }

    protected function getJsonMessage(JsonSchema\Validator $validator)
    {
        $message = '';
        $message .= "JSON does not validate. Violations:\n";
        foreach ($validator->getErrors() as $error) {
            $message .= sprintf("[%s] %s\n", $error['property'], $error['message']);
        }
        return $message;
    }

    protected function getSchema($schemas)
    {
        if (!is_array($schemas)) {
            $schemas = [$schemas];
        }

        $object = [];
        foreach ($schemas as $schema) {
            $object = array_merge_recursive($object, json_decode(file_get_contents(dirname(__DIR__) . '/Adapters/Schemas/' . $schema . '.json'), true));
        }

        return json_decode(json_encode($object));
    }
}

class FakeAdapter extends PaymentAdapter
{
    public function getIntegrationKey()
    {
        return parent::getIntegrationKey();
    }
}
