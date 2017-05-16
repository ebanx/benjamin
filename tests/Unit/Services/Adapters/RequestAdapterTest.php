<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\BoletoRequestAdapter;
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
        $payment = BuilderFactory::payment()->boleto()->businessPerson()->build();

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
            var_dump(json_decode(file_get_contents(dirname(__DIR__) . '/Adapters/Schemas/'.$schema.'.json'), true));
            $object = array_merge_recursive($object, json_decode(file_get_contents(dirname(__DIR__) . '/Adapters/Schemas/'.$schema.'.json'), true));
        }

        var_dump(json_decode(json_encode($object)));

        return json_decode(json_encode($object));
    }
}

class FakeAdapter extends RequestAdapter
{
}
