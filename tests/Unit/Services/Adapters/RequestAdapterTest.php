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

        var_dump($result);

        $validator = new JsonSchema\Validator;
        $validator->validate($result,(object) [
            'type' => 'object',
            'properties' => (object)[
                'integration_key' => (object)[
                    'type' => 'string',
                    'required' => true
                ],
                'operation' => (object)[
                    'type' => 'string',
                    'required' => true
                ],
                'mode' => (object)[
                    'type' => 'string',
                    'required' => true
                ],
                'bypass_boleto_screen' => (object)[
                    'type' => 'boolean'
                ],
                'person_type' => (object)[
                    'type' => 'string',
                    'required' => true
                ],
                'payment' => (object)[
                    'type' => 'object',
                    'required' => true
                ]
            ]
        ],Constraint::CHECK_MODE_EXCEPTIONS);

        $this->assertTrue($validator->isValid());
    }
}
