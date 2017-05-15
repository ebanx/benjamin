<?php
namespace Tests\Unit\Services\Adapters;

use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Services\Adapters\RequestAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use Tests\TestCase;
use JsonSchema;
use JsonSchema\Constraints\Constraint;

class RequestAdapterTest extends TestCase
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $payment = BuilderFactory::payment()->boleto()->businessPerson()->build();

        $adapter = new RequestAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, json_decode(file_get_contents(dirname(__DIR__). '/Adapters/requestSchema.json')), Constraint::CHECK_MODE_EXCEPTIONS);

        $this->assertTrue($validator->isValid());
    }
}
