<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\RequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\TestCase;
use JsonSchema;

class RequestAdapterTest extends TestCase
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

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema('requestSchema'));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
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
            $schemas = array($schemas);
        }

        $object = array();
        foreach ($schemas as $schema) {
            $object = array_merge_recursive($object, json_decode(file_get_contents(dirname(__DIR__) . '/Adapters/Schemas/'.$schema.'.json'), true));
        }

        return json_decode(json_encode($object));
    }
}

class FakeAdapter extends RequestAdapter
{
}
